// assets/controllers/account_dropdown_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['menu'];

    toggle(event) {
        event.stopPropagation();
        this.menuTarget.classList.toggle('hidden');
    }

    connect() {
        document.addEventListener('click', this._closeOutside.bind(this));
    }

    disconnect() {
        document.removeEventListener('click', this._closeOutside.bind(this));
    }

    _closeOutside(event) {
        if (!this.element.contains(event.target)) {
            this.menuTarget.classList.add('hidden');
        }
    }
}
