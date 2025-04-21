import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Confirmation de suppression
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-confirm');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });
    
    // Calculer le total pour l'achat de billets
    const quantitySelect = document.getElementById('quantity');
    const totalPriceElement = document.getElementById('total-price');
    
    if (quantitySelect && totalPriceElement) {
        const unitPrice = parseFloat(totalPriceElement.dataset.unitPrice);
        
        quantitySelect.addEventListener('change', function() {
            const quantity = parseInt(this.value);
            const totalPrice = (quantity * unitPrice).toFixed(2);
            totalPriceElement.textContent = totalPrice.replace('.', ',') + ' €';
        });
    }
    
    // PayPal integration
    const paypalButton = document.getElementById('paypal-button');
    
    if (paypalButton) {
        const amount = paypalButton.dataset.amount;
        const eventId = paypalButton.dataset.eventId;
        
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: amount
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Call your server to save the transaction
                    const paymentForm = document.getElementById('payment-form');
                    const paymentIdInput = document.createElement('input');
                    paymentIdInput.type = 'hidden';
                    paymentIdInput.name = 'payment_id';
                    paymentIdInput.value = details.id;
                    
                    const payerIdInput = document.createElement('input');
                    payerIdInput.type = 'hidden';
                    payerIdInput.name = 'payer_id';
                    payerIdInput.value = details.payer.payer_id;
                    
                    paymentForm.appendChild(paymentIdInput);
                    paymentForm.appendChild(payerIdInput);
                    paymentForm.submit();
                });
            }
        }).render('#paypal-button');
    }
});