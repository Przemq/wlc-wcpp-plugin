class WCPP_ExpirationDateHandler {
  constructor($) {
    this.$ = $;

    this.datePickerSelector = '.datetime-picker';
    this.expirationCheckboxSelector = '#_wcpp_is_set_expiration';
    this.expirationFieldSelector = '.form-field._wcpp_promoted_expiration_date_field';

    this.init();
  }

  init() {
    this.initializeDatePicker();
    this.toggleExpirationDateField();
    this.bindEvents();
  }

  initializeDatePicker() {
    this.$(this.datePickerSelector).flatpickr({
      enableTime: true,
      dateFormat: "Y-m-d H:i",
    });
  }

  toggleExpirationDateField() {
    if (this.$(this.expirationCheckboxSelector).is(':checked')) {
      this.$(this.expirationFieldSelector).show();
    } else {
      this.$(this.expirationFieldSelector).hide();
    }
  }

  bindEvents() {
    this.$(this.expirationCheckboxSelector).change(() => {
      this.toggleExpirationDateField();
    });
  }
}

jQuery(document).ready(function($) {
  new WCPP_ExpirationDateHandler($);
});
