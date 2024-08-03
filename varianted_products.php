<?php
/*
Plugin Name: Product Variant XML
Plugin URI: http:/github.com/yoscibacco/varianted-products
Description: A plugin to add colour and size variants to the XML for WooCommerce products with the "varianted" tag.
Version: 0.1
Author: Can Bacco
Author URI: http:/github.com/yoscibacco/
Update URI: http:/github.com/yoscibacco/varianted-products
GitHub Plugin URI: yoscibacco/varianted-products
GitHub Branch: main
*/
// Function to get product color
function get_product_colour($product_id) {
    // Get the product object
    $product = wc_get_product($product_id);
    
    if (!$product) {
        return 'Product not found';
    }
    
    // Initialize variable to hold the Colour attribute
    $colour = '';

    // Get all attributes of the product
    $product_attributes = $product->get_attributes();

    // Loop through each attribute
    foreach ($product_attributes as $attribute) {
        // Check if the attribute is 'pa_colour'
        if ($attribute->get_name() == 'pa_renk') {
            if ($attribute->is_taxonomy()) {
                // Get terms for taxonomy-based attributes
                $terms = wc_get_product_terms($product_id, $attribute->get_name(), array('fields' => 'names'));
                $colour = implode(', ', $terms);
            } else {
                // Get value for custom attributes
                $colour = implode(', ', $attribute->get_options());
            }
        }
    }
	
	        // If the "varianted" tag is present, output the specified format
        if (!empty($colour)) {
            $colour = "&lt;renk&gt;Renk&lt;/renk&gt;&lt;Renkürün&gt;$colour&lt;/Renkürün&gt;";
			$colour = html_entity_decode($colour);
        }

    return $colour;
}
// Function to get product size

function get_product_size($product_id) {

    // Get the product object
    $product = wc_get_product($product_id);
    
    if (!$product) {
        return 'Product not found';
    }
    
    // Initialize variable to hold the Size attribute
    $size = '';

    // Check if the product has the "varianted" tag
    if (has_term('varianted', 'product_tag', $product_id)) {
        // Get all attributes of the product
        $product_attributes = $product->get_attributes();

        // Loop through each attribute
        foreach ($product_attributes as $attribute) {
            // Check if the attribute is 'pa_size'
            if ($attribute->get_name() == 'pa_beden') {
                if ($attribute->is_taxonomy()) {
                    // Get terms for taxonomy-based attributes
                    $terms = wc_get_product_terms($product_id, $attribute->get_name(), array('fields' => 'names'));
                    $size = implode(', ', $terms);
                } else {
                    // Get value for custom attributes
                    $size = implode(', ', $attribute->get_options());
                }
            }
        }

        // If the "varianted" tag is present, output the specified format
        if (!empty($size)) {
            $size = "&lt;beden&gt;Beden&lt;/beden&gt;&lt;Bedenürün&gt;$size&lt;/Bedenürün&gt;";
			//$size = htmlentities($size);
			//$size = htmlspecialchars_decode($size);
			$size = html_entity_decode($size);
        }
    }

    return $size;
}
// Function to get product size

function get_product_variants($product_id) {
	    // Get the product object
    $product = wc_get_product($product_id);
    
    if (!$product) {
        return 'Product not found';
    }

    // Initialize variables to hold the Colour and Size attributes
    $colour = '';
    $size = '';
    // Check if the product has the "varianted" tag
    if (has_term('varianted', 'product_tag', $product_id)) {
		   // Get all attributes of the product
    		$product_attributes = $product->get_attributes();
		 // Loop through each attribute to get Colour and Size
    foreach ($product_attributes as $attribute) {
        if ($attribute->get_name() == 'pa_renk') {
            if ($attribute->is_taxonomy()) {
                // Get terms for taxonomy-based attributes
                $terms = wc_get_product_terms($product_id, $attribute->get_name(), array('fields' => 'names'));
                $colour = implode(', ', $terms);
            } else {
                // Get value for custom attributes
                $colour = implode(', ', $attribute->get_options());
            }

            // If the "varianted" tag is present, format Colour
            if (!empty($colour)) {
                $colour = "&lt;renk&gt;Renk&lt;/renk&gt;&lt;Renkürün&gt;$colour&lt;/Renkürün&gt;";
            }
        }

        if ($attribute->get_name() == 'pa_beden') {
            if ($attribute->is_taxonomy()) {
                // Get terms for taxonomy-based attributes
                $terms = wc_get_product_terms($product_id, $attribute->get_name(), array('fields' => 'names'));
                $size = implode(', ', $terms);
            } else {
                // Get value for custom attributes
                $size = implode(', ', $attribute->get_options());
            }

            // If the "varianted" tag is present, format Size
            if (!empty($size)) {
                $size = "&lt;beden&gt;Beden&lt;/beden&gt;&lt;Bedenürün&gt;$size&lt;/Bedenürün&gt;";
            }
        }
    }
    // Combine Colour and Size into the desired format
    $variant = "&lt;variants&gt;&lt;variant&gt;$size $colour&lt;/variant&gt;&lt;/variants&gt;";

    // Decode HTML entities
    return html_entity_decode($variant);
	}
}
?>