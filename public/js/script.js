"use strict";

// Homepage
const balanceBtn = document.getElementById(`balance-btn`);
const cardsBtn = document.getElementById(`cards-btn`);
const accounts = document.querySelector(`.accounts`);
const debitCards = document.querySelector(`.debit-cards`);

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