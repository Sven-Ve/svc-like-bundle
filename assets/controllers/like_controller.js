import { Controller } from 'stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static values = {
    url: String,
  }
  static targets = ["counter"];

  async inc(event) {
    event.target.classList.add('like-liked');
    event.target.setAttribute('data-action', '');
    event.target.classList.remove('like-not-liked');

    const response = await fetch(this.urlValue);
    if (response.ok) {
      const result = await response.json();
      this.counterTarget.innerHTML = result.likes;
    }
  }
}