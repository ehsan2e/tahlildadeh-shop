<?php

global $uses;
$uses=array('crud');

/**
 * If the function returns false it means that there was a problem with the picture and any further processes should be cancelled.
 * In this case a meaningful message is generated
 * If it returns true it means that the operator has not provided any picture for the product
 * If it returns a string, the string indicates the name of the picture in the system and hence should be stored in the db for future use.
 * @return bool|string
 */
function handleProductPictureUpload(){
    /**
     * This is the simplified version of the procedure pointed out in http://php.net
     * for a better understanding of the whole procedure refer to the main implementation
     * @see http://php.net/manual/en/features.file-upload.php
     */

    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.

    if (
        !isset($_FILES['picture']['error']) ||
        is_array($_FILES['picture']['error'])
    ) {
        addMessage('خطا در عکس محصول', FAILURE);
        return false;
    }

    // Check $_FILES['picture']['error'] value.

    switch ($_FILES['picture']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return true;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            addMessage('اندازه عکس محصول بزرگ است', FAILURE);
            return false;
        default:
            addMessage('خطای نامشخص در عکس محصول', FAILURE);
            return false;
    }

    // You should also check filesize here.
    if ($_FILES['picture']['size'] > 1000000) {
        addMessage('اندازه عکس محصول بزرگ است', FAILURE);
        return false;
    }

    // DO NOT TRUST $_FILES['picture']['type'] VALUE !!
    // Check MIME Type by yourself.
    // However for the sake of simplicity I changed this part to an unsecure way

    if (false === $ext = array_search($_FILES['picture']['type'],
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ),
            true
        )) {
        addMessage('فرمت فایل قابل قبول نیست',FAILURE);
        return false;
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['picture']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    // we also append uniqid to the name of the file to decrease the chance of overwriting previous files

    $filenName=sprintf('%s.%s', sha1_file($_FILES['picture']['tmp_name']).uniqid(), $ext);

    if (!move_uploaded_file(
        $_FILES['picture']['tmp_name'],
        implode(DS,array(WEBROOT,'media','images','products',$filenName))
    )) {
        addMessage('خطا در انتقال عکس به پوشه فایل', FAILURE);
        return false;
    }

    return $filenName;
}

function main ()
{
    $resp=array();
    if(isset($_POST['submit'])){
        $productData=array(
            'product_name' => isset($_POST['product_name'])?$_POST['product_name']:null,
            'category_id' => isset($_POST['product_category'])?((int) $_POST['product_category']):null,
            'product_is_saleable' => isset($_POST['product_is_saleable'])?((int) $_POST['product_is_saleable']):null,
            'product_price' => isset($_POST['product_price'])?((int) $_POST['product_price']):null,
            'product_stock' => isset($_POST['product_stock'])?((int) $_POST['product_stock']):null,
            'product_brand' => isset($_POST['product_brand'])?((int) ($_POST['product_brand'])):null,
            'product_gender' => isset($_POST['product_gender'])?((int) $_POST['product_gender']):null,
            'product_color' => isset($_POST['product_color'])?($_POST['product_color']):null,
            'product_cloth_type' => isset($_POST['product_cloth_type'])?($_POST['product_cloth_type']):null,


            'product_made_in' => isset($_POST['product_made_in'])?((int) $_POST['product_made_in']):null,
            'product_description' => isset($_POST['product_description'])?$_POST['product_description']:null,
        );

        //TODO implement dedicated validation checks for every piece of data rather than following simple test

        $dataIsCorrect=true;
        foreach($productData as $pieceOfData){
            if(is_null($pieceOfData)){
                addMessage('اطلاعات محصول به درستی وارد نشده است',FAILURE);
                $dataIsCorrect=false;
                break;
            }
        }

        // Handling product picture

        $pictureHandlingResult=handleProductPictureUpload();
        if(is_string($pictureHandlingResult)){
            $productData['product_picture_name']=$pictureHandlingResult;
        }
        if($dataIsCorrect && ($pictureHandlingResult!==false) && create('products',$productData)){
            addMessage(sprintf('"%s" با موفقیت ایجاد شد',htmlentities($productData['product_name'],ENT_QUOTES, 'UTF-8')),SUCSESS);
            return array('redirect'=>BASE_URL.'admin/product/list.php');
        }elseif($dataIsCorrect){
            addMessage('خطا در ذخیره سازی محصول',FAILURE);
        }
    }

    $tempCategories=listRecords('categories');
    $categories=array();
    foreach($tempCategories as $category){
        $categories[$category['id']]=$category['category_name'];
    }
    
    
    //TODO consider a table or config file for country
    $tempCountries = listRecords('countries');
    $countries = array();
    foreach ($tempCountries as $country) {
        $countries[$country['id']] = $country['country_name'];
    }

    /*
     * Load Brands
     */
    $tempBrands = listRecords('brands');
    $brands = array();
    foreach ($tempBrands as $brand) {
        $brands[$brand['id']] = $brand['brand_name'];
    }
    
    
    //TODO consider a table for OS
//    $oses=array(
//        1=>'Windows',
//        2=>'Android',
//        3=>'IOS',
//        4=>'Linux'
//    );
    $resp['data']=array(
        'categories' => $categories,
        'brands' => $brands,
        'countries' => $countries,
        'productData'=>isset($productData)?$productData:array()
    );

    return $resp;

}




require dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'Engine.php';

