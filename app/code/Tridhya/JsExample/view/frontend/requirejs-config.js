var config = {
    map: {
        '*': {
            custom_js: 'Tridhya_JsExample/js/custom_js',
            example: 'Tridhya_JsExample/example',
        }
    },
    shim: {
        'custom_js': {
            deps: ['jquery']
        },
        'example': {
            deps: ['jquery']
        }
    }
};