<?php
/*
  Plugin Name:  Rediriger vers l’article si la recherche ou le tag ne retourne qu’un résultat
  Description:  Cette astuce permet d'améliorer la navigation de votre site en redirigeant vers le seul article d'une recherche fructueuse ou d'un tag. Au lieu de rediriger l’utilisateur vers la page de résultats de recherche et le laisser cliquer sur le lien de l’article, on va directement le rediriger vers l’article correspondant à sa recherche. Ce plugin s'appuie sur une astuce soumise par Jean-David DAVIET (http://goo.gl/TpXl0G).
  Plugin URI:   http://goo.gl/cMVVY7
  Version:      1.2
  Author:       Yvan GODARD
  Author URI:   http://www.yvangodard.me
  Donate link:  https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HJVNXAKDZ5WKE
  License:      GNU GPL V2 - https://www.gnu.org/licenses/gpl-2.0.html
/*
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to:
Free Software Foundation, Inc. 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/

include( 'updater.php' );

add_action('template_redirect', 'redirect_search_to_single_post_result');
 
function redirect_search_to_single_post_result() {
    if( is_search() || is_tag() ) {
        global $wp_query;
        if ($wp_query->post_count == 1) {
            if( $wp_query->posts['0']->post_type == 'post' ){
                wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
            }
        }
    }
}

if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
    $config = array(
        'slug' => 'wp-redirect-to-post-if-only-one-result', // this is the slug of your plugin
        'proper_folder_name' => 'WP-redirect-to-post-if-only-one-result-master', // this is the name of the folder your plugin lives in
        'api_url' => 'https://api.github.com/repos/yvangodard/WP-redirect-to-post-if-only-one-result', // the github API url of your github repo
        'raw_url' => 'https://raw.github.com/yvangodard/WP-redirect-to-post-if-only-one-result/master', // the github raw url of your github repo
        'github_url' => 'https://github.com/yvangodard/WP-redirect-to-post-if-only-one-result', // the github url of your github repo
        'zip_url' => 'https://github.com/yvangodard/WP-redirect-to-post-if-only-one-result/archive/master.zip', // the zip url of the github repo
        'sslverify' => true, // wether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
        'requires' => '3.5', // which version of WordPress does your plugin require?
        'tested' => '3.8', // which version of WordPress is your plugin tested up to?
        'readme' => 'actual_version.txt', // which file to use as the readme for the version number
        'access_token' => '', // Access private repositories by authorizing under Appearance > Github Updates when this example plugin is installed
    );
    new WP_GitHub_Updater($config);
}
