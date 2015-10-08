<?php /**
 * @file
 * Contains \Drupal\dlike\Controller\DefaultController.
 */

namespace Drupal\dlike\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Default controller for the dlike module.
 */
class DefaultController extends ControllerBase {

  public function dlike_user_list($flag_type, $content_id, $flag_name) {
    $flaggers = flag_get_entity_flags($flag_type, $content_id, $flag_name);
    $output = '';
    $output .= "<div class='dlike'>";
    $output .= '<h2>' . \Drupal::config()->get('dlike-modal-window-title-' . $flag_name, NULL) . '</h2>';

    foreach ($flaggers as $flagger) {
      $output .= '<div class="dlike-user-row">' . views_embed_view('dlike_user_view', 'default', $flagger->uid) . '</div>';
    }
    $output .= "</div>";
    print $output;
  }

  public function dlike_append($flag_type, $content_id, $flag_name) {
    // Variables added for appending facebook like like string
    // Check if facebook like likes is enabled for a flag
    $dlike_status_value = \Drupal::config()->get('dlike-' . $flag_name . '_option', 0);


    //add a condition for disabled flags
    if ($dlike_status_value == 0) {
      $dlike_append_link = '';
    }
    else {
      // Get the list of all the users those flagged current content
        // $dlike_append_names = dlike_user_list($type, $flag->get_content_id($object), $flag->name);
        // Get the flag counts for a piece of content
      $dlike_append_count = flag_get_counts($flag_type, $content_id);
      if ($dlike_append_count && $dlike_append_count[$flag_name] > 0) {
        // Get the text string set by the user
        $dlike_text_value = \Drupal::config()->get('dlike-' . $flag_name . '_text_value', NULL);

        // Pass the string through t().
        $dlike_sanitize_string = t('@text', [
          '@text' => $dlike_text_value
          ]);
        // If set, replace the token for count by actual count.
        $dlike_append_string = str_replace('@count', $dlike_append_count[$flag_name], $dlike_sanitize_string);
        // Check if user has the right permissions
        if (\Drupal::currentUser()->hasPermission('dlike access list')) {
          // format link address.
          $dlike_link_address = 'dlike/' . $flag_type . '/' . $content_id . '/' . $flag_name;
          // format the link to the list of users who flagged the content.
          $url = Url::fromRoute('dlike.append');
          $dlike_append_link = '<span class="dlike-' . $flag_type . '-append-' . $content_id .'">' . \Drupal::l(t($dlike_append_string), $url) . '</span>';

        }
        else {
          $dlike_append_link = $dlike_append_string;
        }
      }
      else {
        $dlike_append_link = '<span class="dlike-' . $flag_type . '-append-' . $content_id . '"></span>';
      }
    }
    if (isset($_POST['method']) && $_POST['method'] == 'ajax') {
      print $dlike_append_link;
      die();
    }
    return $dlike_append_link;
  }

}
