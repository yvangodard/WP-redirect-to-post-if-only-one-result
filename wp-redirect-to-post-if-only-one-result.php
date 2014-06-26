<?php
/*
  Plugin Name:  Rediriger vers l’article si la recherche ou le tag ne retourne qu’un résultat
  Description:  Cette astuce permet d'améliorer la navigation de votre site en redirigeant vers le seul article d'une recherche fructueuse ou d'un tag. Au lieu de rediriger l’utilisateur vers la page de résultats de recherche et le laisser cliquer sur le lien de l’article, on va directement le rediriger vers l’article correspondant à sa recherche. Ce plugin s'appuie sur une astuce soumise par Jean-David DAVIET (http://goo.gl/TpXl0G).
  Plugin URI:   http://goo.gl/cMVVY7
  Version:      1.1
  Author:       Yvan GODARD
  Author URI:   http://www.yvangodard.me
  Donate link:  https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HJVNXAKDZ5WKE
/*
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program; if not, write to:
Free Software Foundation, Inc. 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/

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

add_action( 'admin_init', 'ygaup_github_plugin_updater' );
function ygaup_github_plugin_updater()
{
  define( 'WP_GITHUB_FORCE_UPDATE', true );
  include_once( dirname( __FILE__ ) . '/inc/updater.php' );
  include_once( dirname( __FILE__ ) . '/inc/pointers.php' );

  $config = array(
    'slug' => plugin_basename( __FILE__ ),
    'proper_folder_name' => 'WP-redirect-to-post-if-only-one-result-master',
    'api_url' => 'https://api.github.com/repos/yvangodard/WP-redirect-to-post-if-only-one-result',
    'raw_url' => 'https://raw.github.com/yvangodard/WP-redirect-to-post-if-only-one-result/master',
    'github_url' => 'https://github.com/yvangodard/WP-redirect-to-post-if-only-one-result',
    'zip_url' => 'https://github.com/yvangodard/WP-redirect-to-post-if-only-one-result/archive/master.zip',
    'sslverify' => true,
    'requires' => '3.5',
    'tested' => '3.8',
    'readme' => 'readme.txt',
    'access_token' => '',
  );
  new WP_GitHub_Updater( $config );

add_filter( 'plugins_api', 'ygaup_force_info', 11, 3 );
function ygaup_force_info( $bool, $action, $args )
{
  if( $action=='plugin_information' && $args->slug=='wp-redirect-to-post-if-only-one-result' )
    return new stdClass();
  return $bool;
}

add_filter( 'plugins_api_result', 'ygaup_force_info_result', 10, 3 );
function ygaup_force_info_result( $res, $action, $args )
{
  if( $action=='plugin_information' && $args->slug=='wp-redirect-to-post-if-only-one-result' && isset( $res->external ) && $res->external ) {
    $request = wp_remote_get( 'https://raw.github.com/yvangodard/WP-redirect-to-post-if-only-one-result/master/plugin_infos.txt', array( 'timeout' => 30 ) );
    if ( is_wp_error( $request ) ) {
      $res = new WP_Error('plugins_api_failed', '1) '.__( 'An unexpected error occurred. Something may be wrong with Auto Updates Manager or this server&#8217;s configuration.' ), $request->get_error_message() );
    } else {
      $res = maybe_unserialize( wp_remote_retrieve_body( $request ) );
      if ( ! is_object( $res ) && ! is_array( $res ) )
        $res = new WP_Error('plugins_api_failed', '2) '.__( 'An unexpected error occurred. Something may be wrong with Auto Updates Manager or this server&#8217;s configuration.' ), wp_remote_retrieve_body( $request ) );
    }
  }
  return $res;
}