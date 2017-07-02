<?php

namespace Drupal\popular_tags\Plugin\Field\FieldWidget;

use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation OVERRIDING the 'entity_reference_autocomplete_tags' widget.
 */
class EntityReferenceAutocompleteTagsWidget extends \Drupal\Core\Field\Plugin\Field\FieldWidget\EntityReferenceAutocompleteTagsWidget {
  
  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'popular_tags' => [
        'use' => FALSE,
        'limit' => 20,
        'showhide' => TRUE,
      ],
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);
    $settings = $this->getSettings();
    /* If this is a taxonomy_autocomplete widget then add the popular_tags
     * checkbox to the field edit form. We don't have to do anything to save
     * this setting since the form element path is instance/widget/settings/... */
    $element['popular_tags'] = array(
      '#type' => 'fieldset',
      '#title' => t('Popular Tags'),
      '#description' => t('Present the users with most popular terms and allow them to select tags by clicking on them.'),
    );
    $element['popular_tags']['use'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use Clickable Popular Tags?'),
      '#default_value' => $settings['popular_tags']['use'],
    );
    $element['popular_tags']['limit'] = array(
      '#type' => 'number',
      '#title' => t('Limit'),
      '#description' => t('How many (at most) to show? Leave empty if no limit.'),
      '#default_value' => $settings['popular_tags']['limit'],
      '#states' => array(
        'visible' => array(
          ':input[name="fields[field_tags][settings_edit_form][settings][popular_tags][use]"]' => array('checked' => TRUE),
        ),
      ),
    );
    $element['popular_tags']['showhide'] = [
      '#type' => 'checkbox',
      '#title' => t('Show/Hide Links?'),
      '#description' => t('Should the user be presented with "Show All" and "Show Popular" links? These links allow the user to view only the popular tags, or view all tags.'),
      '#default_value' => $settings['popular_tags']['showhide'],
      '#states' => [
        'visible' => [
          ':input[name="fields[field_tags][settings_edit_form][settings][popular_tags][use]"]' => ['checked' => TRUE],
          [':input[name="fields[field_tags][settings_edit_form][settings][popular_tags][limit]"]' => ['!value' => (string) '']],
          'or',
          [':input[name="fields[field_tags][settings_edit_form][settings][popular_tags][limit]"]' => ['!value' => (string) 0]],
        ],
      ],
    ];
    return $element;
  }
}
