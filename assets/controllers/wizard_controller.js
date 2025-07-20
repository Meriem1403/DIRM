// assets/controllers/wizard_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['step', 'prevBtn', 'nextBtn', 'submitBtn'];
    static values  = { current: Number };

    connect() {
        this.currentValue = 1;
        this.max = this.stepTargets.length;
        this.update();
    }

    next() {
        if (this.currentValue < this.max) {
            this.currentValue++;
            this.update();
        }
    }

    prev() {
        if (this.currentValue > 1) {
            this.currentValue--;
            this.update();
        }
    }

    update() {
        this.stepTargets.forEach((el, i) => el.hidden = (i + 1 !== this.currentValue));
        this.prevBtnTarget.hidden   = this.currentValue === 1;
        this.nextBtnTarget.hidden   = this.currentValue === this.max;
        this.submitBtnTarget.hidden = this.currentValue !== this.max;
    }
}
