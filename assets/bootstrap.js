// assets/bootstrap.js
import './styles/app.css';

import { startStimulusApp } from '@symfony/stimulus-bridge';

// Registers all controllers in assets/controllers/**/*_controller.js
export const app = startStimulusApp(
    require.context(
        '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
        true,
        /\.[jt]sx?$/
    )
);
