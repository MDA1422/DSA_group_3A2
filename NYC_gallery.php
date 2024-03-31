<?php   

echo '<head><link rel="stylesheet" href="flickr_c.css"><style>body {
    background-color: #333; /* Dark background color */
    }</style></head>';

$tag = 'newyorkcitybuildings,newyorkcitypeople,empirestatebuilding,nycnight'; // Specify the tag here
$url = 'https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key=c6fe1464a578e7126b3140f055d2e597&tags='.$tag.'&format=json&nojsoncallback=1';

$data = json_decode(file_get_contents($url));

if ($data && $data->stat == 'ok') {
    $photos = $data->photos->photo;

    // Output the gallery container
    echo '<div class="gallery">';
    
    foreach ($photos as $photo) {
        $photo_url = 'http://farm'.$photo->farm.'.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'.jpg';
        echo '<div class="image-container">';
        echo '<img src="'.$photo_url.'" class="flickr-image">';
        echo '</div>';
    }

    // Close the gallery container
    echo '</div>';
} else {
    echo 'Failed to fetch images from Flickr.';
}
?>
