@mkdir('uploads/places', 0777, true);
@mkdir('uploads/gallery', 0777, true);

// Create dummy files so images don't show as broken
$places = [
    'eiffel.jpg', 'colosseum.jpg', 'greatwall.jpg', 'machupicchu.jpg',
    'tajmahal.jpg', 'sydneyopera.jpg', 'pyramids.jpg', 'santorini.jpg',
    'grandcanyon.jpg', 'angkorwat.jpg', 'mtfuji.jpg', 'liberty.jpg'
];

foreach ($places as $img) {
    $file = 'uploads/places/' . $img;
    if (!file_exists($file)) {
        file_put_contents($file, ''); // Create empty file
    }
}

for ($i = 1; $i <= 12; $i++) {
    $file = 'uploads/gallery/gallery' . $i . '.jpg';
    if (!file_exists($file)) {
        file_put_contents($file, ''); // Create empty file
    }
}

echo "Dummy files created! Now your website won't show broken images.";
echo "<br>You can replace them with real images later.";
echo "<br><a href='index.php'>Go to Homepage</a>";
?>