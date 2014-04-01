<?php

/**
 * Luis Guillén Civera Library
 *
 * LICENSE
 *
 *    This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @category   LGC
 * @package    view
 * @copyright  Copyright (c) 2010 Luis Guillén Civera
 * @license    http://www.gnu.org/licenses/agpl-3.0.txt
 * @author     Luis Guillén Civera
 * @version    0.1
 */


class LGC_View_Helper_SimpleMenu extends Zend_View_Helper_FormElement
{

    public function simpleMenu($menu)
    {

        function cmp($a, $b) {
            if(!isset($b['order'])) {
                return 1;
            }
            
            if(!isset($a['order'])) {
                return -1;
            }

            $aorder = (int)$a['order'];
            $border = (int)$b['order'];
            if($aorder == $border) {
                return 0;
            }
            return ($aorder < $border) ? -1 : 1;
        }

        //ordena las secciones
        usort($menu, "cmp");

        //ordena los elementos
        for($i=0;$i<count($menu);$i++) {
            usort($menu[$i]['items'], "cmp");
        }

        $baseUrl = new Zend_View_Helper_BaseUrl();
        $html = "";
        foreach($menu as $section) {

            $html.= "<h4>".$section['text']."</h4>\n";
            $html.= "<ol>";
            foreach($section['items'] as $item) {
                switch((int)$item['href']['type']) {
                    case 1:
                        $html.='<li><a href="'.$baseUrl->baseUrl().$item['href']['link'].'">'.$item['text'].'</a></li>';
                        break;
                    case 2:
                        $html.='<li><a href="'.$item['href']['link'].'">'.$item['text'].'</a></li>';
                        break;
                }
                
            }
            $html.= "</ol>";
        }

        return $html;
    }

}

