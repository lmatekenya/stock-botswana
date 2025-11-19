// assets/stimulus_bootstrap.js
import { Application } from '@hotwired/stimulus';

// Start Stimulus
const application = Application.start();

// Auto-load controllers from the controllers folder
const context = require.context('./controllers', true, /_controller\.js$/);
context.keys().forEach((key) => {
    const controllerModule = context(key);
    // Stimulus expects default export in each controller
    application.register(
        key.replace(/^\.\/(.*)_controller\.js$/, '$1'),
        controllerModule.default
    );
});
