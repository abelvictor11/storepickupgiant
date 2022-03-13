/*global define*/
define([
    'underscore',
    'jquery',
    'ko',
    'Magento_Ui/js/form/form',
    'matchMedia',
    'Amasty_StorePickupWithLocator/js/model/pickup',
    'Amasty_StorePickupWithLocator/js/model/pickup/pickup-data-resolver'    
], function(
    _,
    $,
    ko,    
    Component,
    mediaCheck,
    pickup,
    pickupDataResolver
    
) {
    'use strict';

    var storeCurbsideEnabled = ko.observable();

    return Component.extend({

        visibleComputed: ko.pureComputed(function () {

            //console.log(pickupDataResolver.storeId());
            //console.log(pickup.isPickup());
            //console.log(storeCurbsideEnabled());

            return false;

            return Boolean(pickupDataResolver.storeId() && pickup.isPickup() );
            //return true;
            //return Boolean(pickupDataResolver.storeId() && pickup.isPickup() && storeCurbsideEnabled());
        }),

        initObservable: function () {

            this._super()
                .observe([
                    'visible',
                    'curbsideChecked',
                ]);

            this.visibleComputed.subscribe(this.visible);

            return this;
        },


        initConfig: function () {

            this._super();
            this.visible = this.visibleComputed();

        },  
        initialize: function () {
            this._super();
            // component initialization logic
            return this;
        },

        /**
         * Form submit handler
         *
         * This method can have any name.
         */
        onSubmit: function() {
            // trigger form validation
            this.source.set('params.invalid', false);
            this.source.trigger('customCheckoutForm.data.validate');

            // verify that form data is valid
            if (!this.source.get('params.invalid')) {
                // data is retrieved from data provider by value of the customScope property
                var formData = this.source.get('customCheckoutForm');
                // do something with form data
                console.dir(formData);
            }
        }
    });
});