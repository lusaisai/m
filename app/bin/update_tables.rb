#! /usr/bin/ruby

require 'mysql2'

class UpdateTables
  @@IMAGE_SUFFIXES = [ "jpg", "jpeg", "png" ]
  @@MUSIC_SUFFIXES = [ "mp3", "ape", "flac", "m4a" ]
  @@MUSIC_DIR = "/var/www/music"
  #@@MUSIC_DIR = "/media/lusaisai/Collection/Music"
  
  # The constuctor creates the client connection and clean up the working tables
  def initialize( database, username, password )
     @database = database
     @username = username
     @password = password
     @client = Mysql2::Client.new(:username => @username, :password => @password, :database => @database )
     stageTableClean
     sync_auto_incre
  end
  
  # This method cleans up working tables
  def stageTableClean
     tables = [ "artist_new", "artist_w", "album_new", "album_w", "song_new", "song_w", "image_new", "image_w" ]
     tables.each { |x| @client.query "delete from #{x}" }
  end
  
  def sync_auto_incre
    tables = [ "artist", "album", "song", "image" ]
    tables.each do |x|
      rs = @client.query "select coalesce( max(id) + 1, 1 ) as cnt from #{x}"
      rs.each do |row|
        count = row["cnt"]
        @client.query "ALTER TABLE #{x}_new AUTO_INCREMENT = #{count}"
      end
    end
  end
  
  # This method inserts data into the artist new table
  def insertArtistNew
    Dir.foreach(@@MUSIC_DIR) do |artistName|
      next if ( artistName == "." || artistName == ".." )
      puts "Found #{artistName} ..."
      @client.query "insert into artist_w (name) values( '#{@client.escape artistName}' )"
    end
    
    query = <<-EOF
    insert into artist_new
    select t.id, w.name, t.region, t.info, t.insert_ts, t.update_ts
    from artist_w w
    left join artist t
    on   w.name = t.name
    EOF
    @client.query query
  end
  
  # This method inserts data into the album new table
  def insertAlbumNew
    Dir.foreach(@@MUSIC_DIR) do |artistName| # iterate thru artists
      Dir.chdir File.join( @@MUSIC_DIR, artistName )
      next if ( artistName == "." || artistName == ".." )
      
      Dir.foreach( "." ) do |albumName| # iterate thru album
        next if ! File.directory? albumName
        next if ( albumName == "." || albumName == ".." )
        puts "Found #{albumName} from #{artistName} ..."
        @client.query "insert into album_w (name, artist_name) values( '#{@client.escape albumName}', '#{@client.escape artistName}' )"
      end
      
    end
    
    query = <<-EOF
    insert into album_new
    select t.id, w.name, t.language, t.info, a.id, t.insert_ts, t.update_ts
    from album_w w
    left join artist_new a
    on   w.artist_name = a.name
    left join album t
    on   w.name = t.name
    and  a.id = t.artist_id
    EOF
    @client.query query
  end
  
  # This method inserts artist image data into the image new table
  def insertArtistImageNew
    Dir.foreach(@@MUSIC_DIR) do |artistName| # iterate thru artists
      Dir.chdir File.join( @@MUSIC_DIR, artistName )
      next if ( artistName == "." || artistName == ".." )
      
      Dir.foreach( "." ) do |imageName| # iterate thru images
        next if ! imageName.end_with? *@@IMAGE_SUFFIXES
        puts "Found #{imageName} from #{artistName} ..."
        @client.query "insert into image_w (name, artist_name) values( '#{@client.escape imageName}', '#{@client.escape artistName}' )"
      end
      
    end
    
    query = <<-EOF
    insert into image_new
    select t.id, w.name, a.id, t.album_id, t.insert_ts, t.update_ts
    from image_w w
    left join artist_new a
    on   w.artist_name = a.name
    left join image t
    on   w.name = t.name
    and  a.id = t.artist_id
    and  t.album_id is null
    where w.album_name is null
    EOF
    @client.query query
  end
  
  # This method inserts data into the song new table
  def insertSongNew
    Dir.foreach(@@MUSIC_DIR) do |artistName| # iterate thru artists
      Dir.chdir File.join( @@MUSIC_DIR, artistName )
      next if ( artistName == "." || artistName == ".." )
      
      Dir.foreach( "." ) do |albumName| # iterate thru album
        next if ! File.directory? albumName
        next if ( albumName == "." || albumName == ".." )
        
        Dir.foreach( albumName ) do |songName| # iterate thru songs
          next if ! songName.end_with? *@@MUSIC_SUFFIXES
	      puts "Found #{songName} from #{albumName} from #{artistName} ..."
          @client.query "insert into song_w (name, artist_name, album_name) values( '#{@client.escape songName}', '#{@client.escape artistName}', '#{@client.escape albumName}' )"      
        end
        
        end
      
    end
    
    query = <<-EOF
    insert into song_new
    select t.id, w.name, t.lyric, al.id, t.insert_ts, t.update_ts
    from song_w w
    left join artist_new ar
    on   w.artist_name = ar.name
    left join album_new al
    on   w.album_name = al.name
    
    left join (
      select s.*, a.artist_id
      from song s
      join album a
      on   s.album_id = a.id
    ) t
    on   w.name = t.name
    and  ar.id = t.artist_id
    and  al.id = t.album_id
    EOF
    @client.query query
  end
  
  # This method inserts album image data into the image new table
  def insertAlbumImageNew
    Dir.foreach(@@MUSIC_DIR) do |artistName| # iterate thru artists
      Dir.chdir File.join( @@MUSIC_DIR, artistName )
      next if ( artistName == "." || artistName == ".." )
      
      Dir.foreach( "." ) do |albumName| # iterate thru album
        next if ! File.directory? albumName
        next if ( albumName == "." || albumName == ".." )
        
        Dir.foreach( albumName ) do |imageName| # iterate thru images
          next if ! imageName.end_with? *@@IMAGE_SUFFIXES
	      puts "Found #{imageName} from #{albumName} from #{artistName} ..."
          @client.query "insert into image_w (name, artist_name, album_name) values( '#{@client.escape imageName}', '#{@client.escape artistName}', '#{@client.escape albumName}' )"      
        end
        
        end
      
    end
    
    query = <<-EOF
    insert into image_new
    select t.id, w.name, ar.id, al.id, t.insert_ts, t.update_ts
    from image_w w
    left join artist_new ar
    on   w.artist_name = ar.name
    left join album_new al
    on   w.album_name = al.name
    
    left join image t
    on   w.name = t.name
    and  ar.id = t.artist_id
    and  al.id = t.album_id
    
    where w.album_name is not null
    EOF
    @client.query query
  end
  
  def rename
    [ "image", "song", "album", "artist" ].each do |table|
      @client.query "rename table #{table} to #{table}_old"
      @client.query "rename table #{table}_new to #{table}"
    end
  end

end

ut = UpdateTables.new( "mav", "mav", "mav" );
ut.insertArtistNew
ut.insertArtistImageNew
ut.insertAlbumNew
ut.insertSongNew
ut.insertAlbumImageNew
ut.rename
