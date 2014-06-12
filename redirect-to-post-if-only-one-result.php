<?php
/*
  Plugin Name:  Rediriger vers l’article si la recherche ou le tag ne retourne qu’un résultat
  Description:  Cette astuce permet d'améliorer la navigation de votre site en redirigeant vers le seul article d'une recherche fructueuse ou d'un tag. Au lieu de rediriger l’utilisateur vers la page de résultats de recherche et le laisser cliquer sur le lien de l’article, on va directement le rediriger vers l’article correspondant à sa recherche.
  Plugin URI:   http://goo.gl/TpXl0G
  Version:      1.0
  Author:       Jean-David DAVIET
  Author URI:   http://jeandaviddaviet.fr
 
  /*
  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.
 
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
 
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
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