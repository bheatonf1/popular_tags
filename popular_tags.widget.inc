<?php

/**
 * Utility function to inject the popular tags functionality into a form
 * element.
 * 
 * @param array $element
 * @param type $context 
 */
function _popular_tags_inject(&$element, $context) {
  drupal_add_js(drupal_get_path('module', 'popular_tags') . '/popular_tags.js');
  drupal_add_css(drupal_get_path('module', 'popular_tags') . '/popular_tags.css');
  $vname = $context['field']['settings']['allowed_values'][0]['vocabulary'];
  $terms = _popular_tags_get_terms($vname);
  $div = '<div class="tag-terms">';
  foreach ($terms as $tid => $term) {
    $div .= t('<a class="term" href="#" title="@term">@term</a>', array('@term' => $term));
  }
  $div .= '</div>';
  $element['#description'] = t('You can type your own tags, or choose from the most commonly used tag below.') . $div;
}

/**
 * Utility function to get the array of popular terms (key = tid, value = term name).
 * @param type $vname
 * @param type $count
 * @return type 
 */
function _popular_tags_get_terms($vname, $count = NULL) {
  if (!isset($count)) {
    $count = variable_get('popular_tag_terms_limit', 10);
  }
  $sql = <<<__SQL__
SELECT td.tid, td.name
FROM {taxonomy_term_data} td
INNER JOIN {taxonomy_vocabulary} tv ON (td.vid = tv.vid)
LEFT JOIN {taxonomy_index} tn ON (tn.tid = td.tid)
WHERE tv.name = :vname
GROUP BY td.tid
ORDER BY COUNT(tn.tid) DESC
__SQL__;
  $params = array();
  $params[':vname'] = $vname;
  if ($count !== 0) {
    $query = db_query_range($sql, 0, $count, $params);
  } else {
    $query = db_query($sql, $params);
  }
  $terms = $query->fetchAllKeyed();
  return $terms;
}
