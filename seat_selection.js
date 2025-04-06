document.addEventListener('DOMContentLoaded', () => {
    const seats = document.querySelectorAll('.seat:not(.occupied)');
    const totalFareElement = document.getElementById('total-fare');
    const continueButton = document.getElementById('continue-button');
    let selectedSeats = [];

    seats.forEach(seat => {
        seat.addEventListener('click', () => {
            if (seat.classList.contains('selected')) {
                seat.classList.remove('selected');
                selectedSeats = selectedSeats.filter(s => s !== seat.dataset.seatNumber);
            } else {
                seat.classList.add('selected');
                selectedSeats.push(seat.dataset.seatNumber);
            }
            updateTotalFare();
        });
    });

    const updateTotalFare = () => {
        const fare = selectedSeats.reduce((acc, seatNumber) => {
            const seat = document.querySelector(`.seat[data-seat-number="${seatNumber}"]`);
            return acc + parseFloat(seat.dataset.seatFare);
        }, 0);
        totalFareElement.textContent = `Total Fare: ${fare}`;
        continueButton.disabled = selectedSeats.length === 0;
    };

    continueButton.addEventListener('click', () => {
        const url = `payment_page.html?seats=${selectedSeats.join(',')}`;
        window.location.href = url;
    });
});
