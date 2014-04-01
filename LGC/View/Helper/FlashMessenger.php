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


class LGC_View_Helper_FlashMessenger extends Zend_View_Helper_FormElement
{

    private $_types = array(
        LGC_Controller_Action_Helper_LGCFlashMessenger::ERROR,
        LGC_Controller_Action_Helper_LGCFlashMessenger::WARNING,
        LGC_Controller_Action_Helper_LGCFlashMessenger::NOTICE,
        LGC_Controller_Action_Helper_LGCFlashMessenger::SUCCESS
    );

    public function flashMessenger()
    {
        $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('LGCFlashMessenger');

        $html = '';

        foreach ($this->_types as $type) {
            $messages = $flashMessenger->getMessages($type);

            if (!$messages){
              $messages = $flashMessenger->getCurrentMessages($type);
            }

            if ($messages) {
              if ( !$html ) {
                  $html .= '<ul class="messages">';
              }

              $html .= '<li class="' . $type . '-msg">';
              $html .= '<ul>';

              foreach ( $messages as $message ) {
                  $html.= '<li>';
                  $html.= $message->message;
                  $html.= '</li>';
              }

              $html .= '</ul>';
              $html .= '</li>';
            }
        }

        if ( $html) {
            $html .= '</ul>';
        }

        return $html;
    }

}

