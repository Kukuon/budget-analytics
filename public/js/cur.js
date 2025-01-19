document.addEventListener('DOMContentLoaded', function() {
    const apiKey = '251169340405a39ccef3836d';
    const apiUrl = `https://v6.exchangerate-api.com/v6/${apiKey}/latest/USD`;

    const amountInput = document.getElementById('amount');
    const fromCurrency = document.getElementById('fromCurrency');
    const toCurrency = document.getElementById('toCurrency');
    const result = document.getElementById('result');
    const convertButton = document.getElementById('convert');

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            const currencies = Object.keys(data.conversion_rates);
            currencies.forEach(currency => {
                const optionFrom = document.createElement('option');
                const optionTo = document.createElement('option');
                optionFrom.value = optionTo.value = currency;
                optionFrom.textContent = optionTo.textContent = currency;
                fromCurrency.appendChild(optionFrom);
                toCurrency.appendChild(optionTo);
            });
        })
        .catch(error => {
            console.error('Error fetching the exchange rates:', error);
            result.textContent = 'Не удалось загрузить данные об обменных курсах.';
        });

    convertButton.addEventListener('click', () => {
        const amount = parseFloat(amountInput.value);
        const from = fromCurrency.value;
        const to = toCurrency.value;

        if (isNaN(amount)) {
            result.textContent = '';
            return;
        }

        fetch(`https://v6.exchangerate-api.com/v6/${apiKey}/pair/${from}/${to}`)
            .then(response => response.json())
            .then(data => {
                const rate = data.conversion_rate;
                const convertedAmount = (amount * rate).toFixed(2);
                result.textContent = `${amount} ${from} = ${convertedAmount} ${to}`;
            })
            .catch(error => {
                console.error('Error fetching the conversion rate:', error);
                result.textContent = 'Не удается конверитровать валюту.';
            });
    });
});