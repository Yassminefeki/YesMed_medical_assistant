// notifications.js
// This script handles browser notifications for medication reminders and appointment alerts.

// Check for notification permission and request if not granted
function requestNotificationPermission() {
    if (!("Notification" in window)) {
        console.log("This browser does not support desktop notifications.");
        return;
    }
    if (Notification.permission === "default") {
        Notification.requestPermission().then(function(permission) {
            console.log("Notification permission:", permission);
        });
    }
}

// Show a notification with given title and options
function showNotification(title, options) {
    if (Notification.permission === "granted") {
        new Notification(title, options);
    }
}

// Example function to trigger medication reminder notification
function notifyMedicationReminder(medicationName, time) {
    const title = "Rappel Médicament";
    const options = {
        body: `Il est temps de prendre votre médicament: ${medicationName} à ${time}.`,
        icon: "assets/im/Medicines.png",
        vibrate: [200, 100, 200],
        tag: "medication-reminder"
    };
    showNotification(title, options);
}

// Example function to trigger appointment reminder notification
function notifyAppointmentReminder(appointmentDate, appointmentTime) {
    const title = "Rappel Rendez-vous";
    const options = {
        body: `Vous avez un rendez-vous prévu le ${appointmentDate} à ${appointmentTime}.`,
        icon: "assets/im/Medicines.png",
        vibrate: [200, 100, 200],
        tag: "appointment-reminder"
    };
    showNotification(title, options);
}

// Initialize notifications on page load
document.addEventListener("DOMContentLoaded", function() {
    requestNotificationPermission();

});
