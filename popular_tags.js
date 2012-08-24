(function($) {

Drupal.behaviors.popular_tags = {
  attach: function(context, settings) {
    $('.tag-terms .term', context).click(function(event) {
      $this = $(this);
      var inp = $this.parents('.form-item').find('input:text.form-autocomplete');
      var val = inp.val();
      var term = $this.text();
      if(val.indexOf(term) == 0) {
        return false;
      }
      if(val.indexOf(', ' + term) >= 0) {
        return false;
      }
      if(val) {
        inp.val(val + ', ' + term);
      } else {
        inp.val(term);
      }
      return false;
    });
    if(Drupal.settings.popular_tags && Drupal.settings.popular_tags.popular_tids) {
      for(field_name in Drupal.settings.popular_tags.popular_tids) {
        var _field_name = field_name.replace('_', '-');
        var element_id = 'edit-' + _field_name + '-und';
        var container = $('#' + element_id, context);
        var tids = Drupal.settings.popular_tags.popular_tids[field_name];
        for(t in tids) {
          var tid = tids[t];
          container.find('.form-item-' + _field_name + '-und-' + tid).addClass('popular-term');
        }
        // hide all terms
        var show = $('<a href="#">Show All</a>');
        var hide = $('<a href="#">Show Popular</a>');
        show.click(function() {
          $(this).hide();
          hide.show();
          container.find('.form-item').show();
          return false;
        });
        hide.click(function() {
          $(this).hide();
          show.show();
          container.find('.form-item').hide();
          container.find('.form-item.popular-term').show();
          return false;
        });
        container.after(show);
        container.after(hide);
        hide.click();
      }
    }
  }
}

})(jQuery)
