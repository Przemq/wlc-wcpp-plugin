class PromotedProductBanner {
  constructor(ajaxUrl, nonce) {
    this.ajaxUrl = ajaxUrl;
    this.nonce = nonce;
    this.init();
  }

  init() {
    this.fetchPromotedProduct();
  }

  fetchPromotedProduct() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', this.ajaxUrl, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = () => {
      if (xhr.status >= 200 && xhr.status < 400) {
        this.handleSuccess(xhr.response);
      } else {
        console.error('AJAX error. Status:', xhr.status);
      }
    };

    xhr.onerror = () => {
      console.error('AJAX request failed.');
    };

    const params = new URLSearchParams({
      action: 'wcpp_get_promoted_product',
      nonce: this.nonce
    });

    xhr.send(params.toString());
  }

  handleSuccess(responseText) {
    let response;
    try {
      response = JSON.parse(responseText);
    } catch (e) {
      console.error('Error parsing JSON response:', e);
      return;
    }

    if (response.success) {
      this.displayBanner(response.data);
    } else {
      console.error('Error: ', response.data);
    }
  }

  displayBanner(data) {
    const banner = document.createElement('div');
    banner.id = 'promoted-post-banner';
    banner.style.backgroundColor = data.settings.background_color;
    banner.style.textAlign = 'center';
    banner.style.padding = '10px';

    const link = document.createElement('a');
    link.href = data.permalink;
    link.style.color = data.settings.text_color;
    link.style.textDecoration = 'none';

    const strong = document.createElement('strong');
    strong.textContent = data.settings.title_prefix + ": ";

    link.appendChild(strong);
    link.appendChild(document.createTextNode(data.title));
    banner.appendChild(link);

    const header = document.querySelector('header');
    if (header) {
      header.insertAdjacentElement('afterend', banner);
    } else {
      console.error('Header element not found.');
    }
  }
}
document.addEventListener('DOMContentLoaded', () => {
  new PromotedProductBanner(wcpp_ajax.ajax_url, wcpp_ajax.nonce);
});
