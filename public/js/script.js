"use strict";

// Homepage ///////////////////////////////////////////////////////////////
const balanceBtn = document.getElementById(`balance-btn`);
const cardsBtn = document.getElementById(`cards-btn`);
const accounts = document.querySelector(`.accounts`);
const debitCards = document.querySelector(`.debit-cards`);

const exchangeRatesContainer = document.querySelector(`.exchange-rates-container`);

// Toggle accounts and debit cards ///////////////////////////////////////////////////////////////
const showAccounts = () => {
    accounts.classList.add(`show`);
    debitCards.classList.remove(`show`);
    balanceBtn.classList.add(`active-btn`);
    cardsBtn.classList.remove(`active-btn`);
}

const showDebitCards = () => {
    debitCards.classList.add(`show`);
    accounts.classList.remove(`show`);
    cardsBtn.classList.add(`active-btn`);
    balanceBtn.classList.remove(`active-btn`);
}

balanceBtn.addEventListener(`click`, (e) => {
    e.preventDefault();
    showAccounts();
});

cardsBtn.addEventListener(`click`, (e) => {
    e.preventDefault();
    showDebitCards();
})

// Exchange rates ///////////////////////////////////////////////////////////////
fetch(`http://127.0.0.1:8080/api/v1/exchange-rates`)
.then(res => res.json())
.then(data => {
    
    data.data.forEach(rate => {

        const html = `<div class="exchange-rate">
        <p>Fullname: ${rate.fullname}</p>
        <p>Currency: ${rate.currency}</p>
        <p>Exchange rage: ${rate.exchangeRate}</p>
        </div>`;
        
        exchangeRatesContainer.insertAdjacentHTML(`beforeend`, html);
    })

})
.catch(err => {
    console.log(`Error: ${err.message}`);
});