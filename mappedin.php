<?php
/*
* MIT License
*
* Copyright (c) 2023 Mappedin Inc.
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:

* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.

* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
*
* Plugin Name:          Mappedin
* Plugin URI:           https://developer.mappedin.com/web/v6/embed/mappedin-plugin-for-wordpress/
* Description:          A Wordpress plugin to configure and display Mappedin.
* Text Domain:          mappedin
* Version:              1.0.0
* Requires at least:    2.9.0
* Requires PHP:         7.2
* Author:               Mappedin
* License:              MIT
* License URI:          https://spdx.org/licenses/MIT.html
*/

// Exit if accessed directly - security.
if ( ! defined( 'ABSPATH' ) ) exit; 

//Constants
define('MAPPEDIN_VIEWER_URL', 'mappedin_viewer_url_option');

// Activation and deactivation.
register_activation_hook( __FILE__, 'mappedin_viewer_activation' );
register_deactivation_hook( __FILE__, 'mappedin_viewer_deactivation' );

// Runs once when activated.
function mappedin_viewer_activation() {
    // Nothing needed yet.
}

// Runs once when deactivated.
function mappedin_viewer_deactivation() {
    // Delete the options.
    $del_id = delete_option(MAPPEDIN_VIEWER_URL);
}

// Initialization
add_action( 'init', 'mappedin_viewer_register_shortcodes');

// Shortcode registration.
function mappedin_viewer_register_shortcodes() {
    add_shortcode('mappedin', 'mappedin_viewer_display_shortcode_content');
 }

// Admin page CSS
function mappedin_admin_viewer_page_style() {
    $mappedin_admin_page_css = 
        '<style>
            .mappedinViewerAdmin_table {
                    border-collapse: collapse;
                    color: #333333;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);            
                    margin-top: 50px;
                    margin-bottom: 50px;
            }

            .mappedinViewerAdmin_table th {
                background-color: #BF4320;
                color: #FFFFFF;
                font-weight: bold;
                padding: 10px;
                letter-spacing: 1px;
                border-top: 1px solid #FFFFFF;
                border-bottom: 1px solid #CCCCCC;
            }

            .mappedinViewerAdmin_table tr:nth-child(even) td {
                background-color: #F2F2F2;
            }
            
            .mappedinViewerAdmin_table tr:hover td {
                background-color: #FAFAFA;
            }
            
            .mappedinViewerAdmin_table td {
                background-color: #FFFFFF;
                padding: 10px;
                border-bottom: 1px solid #CCCCCC;
            }
            
            .mappedinViewerAdmin_submit {
                background-color: #BF4320;
                border: none;
                color: white;
                padding: 12px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                margin: 4px 2px;
                cursor: pointer;
                border-radius: 12px;
                font-weight: bold;
        </style>';

        $allowed_html = array(
            'style' => array()
        );

        echo wp_kses( $mappedin_admin_page_css, $allowed_html );

}

// Admin page
function mappedin_display_viewer_config_page() {
    $mappedin_viewer_admin_save_nonce = wp_create_nonce( 'mappedin_viewer_admin_save_nonce' ); 

    $mappedin_viewer_save_status = "";

    if ( isset( $_REQUEST['mappedin_viewer_status'] ) ) {
        if( $_REQUEST['mappedin_viewer_status'] === "1") {
            $mappedin_viewer_save_status = "Saved Successfully";
        } else if( $_REQUEST['mappedin_viewer_status'] === "0") {
            $mappedin_viewer_save_status = "Failed to save configuration";
        }
    }

    $the_admin_url = esc_url( admin_url( 'admin-post.php' ));
    $mappedin_url =  esc_html( get_option(MAPPEDIN_VIEWER_URL, "" ));

    $mappedin_viewer_config_html = 
        '<div class="wrap">            
            <h2>Mappedin Configuration</h2>
            <form name="mappedinViewerOptions" method="post" action="' . $the_admin_url . '">
                <input type="hidden" name="action" value="mappedin_viewer_form_response">
                <input type="hidden" name="mappedin_viewer_admin_save_nonce" value="' . $mappedin_viewer_admin_save_nonce . '" />
                <table class="mappedinViewerAdmin_table">
                    <tr>
                        <th><b>Parameter</b></hd>
                        <th><b>Value</b></hd>
                    </tr>
                    <tr>
                        <td><b>Map URL: </b></td>
                        <td><input type="text" name="mappedin_viewer_url" id="mappedin_viewer_url" size ="55" value="' . $mappedin_url . '"></td>
                    </tr>
                    <tr>
                        <td></td><td align="right"><input type="Submit" value="Save" id="submit" class="mappedinViewerAdmin_submit {
                            "></td>
                    </tr>
                </table>
            </form>
            <p>Refer to the <a href="https://developer.mappedin.com/web/v6/embed/mappedin-plugin-for-wordpress/" target="_new">Mappedin Plugin for WordPress Guide</a> for more information on these parameters.</p>
            <p><b>' . $mappedin_viewer_save_status . ' </b></p>
        </div>';

        $allowed_html = array(
            'div' => array(
                'class' => array()
            ),
            'h2' => array(),
            'form' => array(
                'name' => array(),
                'method' => array(),
                'action' => array()
            ),
            'input' => array(
                'type' => array(),
                'name' => array(),
                'id' => array(),
                'size' => array(),
                'value' => array()
            ),
            'table' => array(
                'class' => array()
            ),
            'tr' => array(),
            'th' => array(),
            'td' => array(),
            'b' => array(),
            'a' => array(
                'href' => array(),
                'target' => array()
            ),
            'p' => array(),
        );

        echo wp_kses( $mappedin_viewer_config_html, $allowed_html );
        
  }

  function mappedin_viewer_admin_menu() {
    $base64_svg_icon = 'PHN2ZyB3aWR0aD0iNTgiIGhlaWdodD0iOTIiIHZpZXdCb3g9IjAgMCA1OCA5MiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwKSI+CjxwYXRoIGQ9Ik01My45OSAyOC42Mjc4SDQ0LjMyNzRDNDEuODg3MyAyOC42Mjc4IDQwLjcxNjEgMjkuNzA5NCA0MC43MTYxIDMyLjA2OTJWNjEuNzE0MkwyMS4wNDkxIDMxLjIzMzRDMTkuNjgyNyAyOS4xMTk0IDE4LjgwNDIgMjguNzI2MSAxNi4wNzE0IDI4LjYyNzhINi41NTUxQzQuMDE3NDIgMjguNjI3OCAyLjg0NjE5IDI5LjcwOTQgMi44NDYxOSAzMi4wNjkyVjg4LjM2MDRDMi44NDYxOSA5MC43MjAyIDQuMDE3NDIgOTEuODAxOCA2LjU1NTEgOTEuODAxOEgxNi4yMTc4QzE4Ljc1NTQgOTEuODAxOCAxOS45MjY3IDkwLjcyMDIgMTkuOTI2NyA4OC4zNjA0VjU4Ljg2MjhMMzkuNjkxMiA4OS4xOTYyQzQxLjE1NTMgOTEuNDA4NSA0MS45MzYxIDkxLjgwMTggNDQuNjY5IDkxLjgwMThINTQuMDM4OEM1Ni41NzY1IDkxLjgwMTggNTcuNzQ3NyA5MC43MjAyIDU3Ljc0NzcgODguMzYwNFYzMi4wNjkyQzU3LjY1MDEgMjkuNzA5NCA1Ni40Nzg5IDI4LjYyNzggNTMuOTkgMjguNjI3OFoiIGZpbGw9IiMyRTJFMkUiLz4KPHBhdGggZD0iTTExLjM4NjMgMjIuMjM2N0MxNy4yNjE4IDIyLjIzNjcgMjIuMDI1IDE3LjQzODMgMjIuMDI1IDExLjUxOTJDMjIuMDI1IDUuNjAwMTIgMTcuMjYxOCAwLjgwMTc1OCAxMS4zODYzIDAuODAxNzU4QzUuNTEwNjcgMC44MDE3NTggMC43NDc1NTkgNS42MDAxMiAwLjc0NzU1OSAxMS41MTkyQzAuNzQ3NTU5IDE3LjQzODMgNS41MTA2NyAyMi4yMzY3IDExLjM4NjMgMjIuMjM2N1oiIGZpbGw9IiMyRTJFMkUiLz4KPC9nPgo8ZGVmcz4KPGNsaXBQYXRoIGlkPSJjbGlwMCI+CjxyZWN0IHdpZHRoPSI1NyIgaGVpZ2h0PSI5MSIgZmlsbD0id2hpdGUiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAuNzQ3NTU5IDAuODAxNzU4KSIvPgo8L2NsaXBQYXRoPgo8L2RlZnM+Cjwvc3ZnPg==';

    $icon_data_uri = 'data:image/svg+xml;base64,' . $base64_svg_icon;

    add_menu_page(
          'Mappedin Configuration',         // Page title
          'Mappedin',                       // Menu title
          'manage_options',                        // Capability
          'mappedin-viewer-config',                // Menu slug
          'mappedin_display_viewer_config_page',   // Callback function
          $icon_data_uri                           // Menu icon
      );
  }

  function mappedin_viewer_admin_form_response() {
    if( isset( $_POST['mappedin_viewer_admin_save_nonce'] ) && 
        wp_verify_nonce( sanitize_text_field ( wp_unslash( $_POST['mappedin_viewer_admin_save_nonce'])), 'mappedin_viewer_admin_save_nonce')) {
        $mappedin_viewer_url = sanitize_text_field( $_POST['mappedin_viewer_url'] );

        update_option(MAPPEDIN_VIEWER_URL, $mappedin_viewer_url );

        $save_status = "1"; // Success!

		// Redirect to the admin page with save status.
		mappedin_redirect_to_viewer_admin( $save_status);
		exit;

    } else {
        wp_die( __( 'Invalid nonce specified', 'mappedin-viewer' ), __( 'Error', 'mappedin-viewer' ), array(
            'response' 	=> 403,
            'back_link' => 'admin.php?page=' . 'mappedin-viewer',

    ) );
    }

  }

  function mappedin_redirect_to_viewer_admin( $save_status) {
    wp_redirect( esc_url_raw( add_query_arg( array(
                                'mappedin_viewer_status' => $save_status
                                ),
                        admin_url('admin.php?page=mappedin-viewer-config') 
                ) ) );

}

  add_action('admin_menu', 'mappedin_viewer_admin_menu');
  add_action('admin_post_mappedin_viewer_form_response', 'mappedin_viewer_admin_form_response');
  add_action('admin_head', 'mappedin_admin_viewer_page_style');

function mappedin_viewer_display_shortcode_content() {
    $mappedin_viewer_url = esc_html ( get_option(MAPPEDIN_VIEWER_URL, "" ));

    $ret_val = 
        '<p>Configure the Mappedin map URL in the Wordpress Mappedin Web admin page.</p>
        <p>Refer to the <a href="https://developer.mappedin.com/web/v6/embed/mappedin-plugin-for-wordpress/" target="_new">Mappedin Plugin for WordPress Guide</a> for more information on these parameters.</p>
        ';

    if (strlen($mappedin_viewer_url) > 0) {     
        // Parameters have been configured, display Mappedin Viewer.  
        $ret_val =  
            '<div>
                <iframe
                    href="https://www.mappedin.com/"
                    title="Mappedin Map"
                    name="Mappedin Map"
                    scrolling="no"
                    width="100%"
                    height="650"
                    frameborder="0"
                    style="border:0"
                    src="' . $mappedin_viewer_url . '"
                ></iframe>
            </div>';
    }

    return($ret_val);
}
