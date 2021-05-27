<?php
function is_404($url) {
    $handle = curl_init($url);
    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

    /* Get the HTML or whatever is linked in $url. */
    $response = curl_exec($handle);

    /* Check for 404 (file not found). */
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);

    /* If the document has loaded successfully without any redirection or error */
    if ($httpCode >= 200 && $httpCode < 300) {
        return false;
    } else {
        return true;
    }
}

function image_view1($img_path) {
	$IMAGE_PATH = $img_path;
		list($IMAGE_W, $IMAGE_H, $IMAGE_TYPE) = getimagesize($IMAGE_PATH);

		switch ($IMAGE_TYPE) {

			case 1: $image = imagecreatefromgif($IMAGE_PATH); break;

			case 2: $image = imagecreatefromjpeg($IMAGE_PATH);  break;

			case 3: $image = imagecreatefrompng($IMAGE_PATH); break;

			case 15: $image = imagecreatefromwbmp($IMAGE_PATH); break;

			default: return '';

		}

		ob_start();

		imagejpeg( $image, NULL, 100 );

		imagedestroy( $image );

		$iraw = ob_get_clean();
    $src = "data:image/jpeg;base64,".base64_encode($iraw);
		return $src;

}

 ?>
