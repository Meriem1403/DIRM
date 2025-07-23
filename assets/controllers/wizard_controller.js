// assets/controllers/wizard_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['step', 'prevBtn', 'nextBtn', 'submitBtn'];

    connect() {
        // on démarre à l’étape 1
        this.currentStep = 1;
        this.maxSteps    = this.stepTargets.length;

        // si un champ présente une erreur, on saute à son step
        const errorEl = this.element.querySelector('.invalid-feedback:not(:empty), .form-error:not(:empty)');
        if (errorEl) {
            const parentStep = errorEl.closest('[data-wizard-target="step"]');
            const val = parseInt(parentStep?.dataset.wizardStepValue, 10);
            if (!isNaN(val) && val >= 1 && val <= this.maxSteps) {
                this.currentStep = val;
            }
        }

        this.update();
    }

    // appelé par data-action="click->wizard#nextStep"
    nextStep() {
        if (this.currentStep < this.maxSteps) {
            this.currentStep++;
            this.update();
        }
    }

    // appelé par data-action="click->wizard#prevStep"
    prevStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.update();
        }
    }

    // gère l’affichage des steps et des boutons
    update() {
        // n’afficher que la step courante
        this.stepTargets.forEach((el, i) => {
            el.hidden = (i + 1 !== this.currentStep);
        });

        if (this.hasPrevBtnTarget) {
            this.prevBtnTarget.hidden = (this.currentStep === 1);
        }
        if (this.hasNextBtnTarget) {
            this.nextBtnTarget.hidden = (this.currentStep === this.maxSteps);
        }
        if (this.hasSubmitBtnTarget) {
            this.submitBtnTarget.hidden = (this.currentStep !== this.maxSteps);
        }
    }
}
