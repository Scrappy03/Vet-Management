/**
 * Test script to validate the appointment API functionality
 * This can be run in the browser console for debugging
 */

function testCreateAppointment() {
    const sampleAppointment = {
        patient_id: 1, // Replace with actual ID
        staff_id: 1, // Replace with actual ID
        appointment_type: "Check-up",
        start_time: "2025-05-30T09:00:00",
        notes: "Test appointment",
        care_status: "",
        status: "upcoming"
    };

    console.log("Testing appointment creation with:", sampleAppointment);

    return fetch('api/appointments.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        credentials: 'same-origin',
        body: JSON.stringify(sampleAppointment)
    })
        .then(response => {
            console.log("Response status:", response.status);
            return response.json();
        })
        .then(data => {
            console.log("Create appointment response:", data);
            if (data.success && data.appointment_id) {
                console.log("Success! Created appointment ID:", data.appointment_id);
                return data.appointment_id;
            } else {
                console.error("Error creating appointment:", data.error || "Unknown error");
                return null;
            }
        })
        .catch(err => {
            console.error("Error in API call:", err);
            return null;
        });
}

function testUpdateAppointment(appointmentId) {
    if (!appointmentId) {
        console.error("No appointment ID provided for update test");
        return Promise.reject("No appointment ID");
    }

    const updatedAppointment = {
        appointment_id: appointmentId,
        patient_id: 1, // Replace with actual ID
        staff_id: 1, // Replace with actual ID
        appointment_type: "Vaccination",
        start_time: "2025-05-30T10:00:00",
        notes: "Updated test appointment",
        care_status: "anxious",
        status: "upcoming"
    };

    console.log("Testing appointment update with:", updatedAppointment);

    return fetch('api/appointments.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        credentials: 'same-origin',
        body: JSON.stringify(updatedAppointment)
    })
        .then(response => {
            console.log("Response status:", response.status);
            return response.json();
        })
        .then(data => {
            console.log("Update appointment response:", data);
            if (data.success) {
                console.log("Success! Updated appointment ID:", appointmentId);
                return true;
            } else {
                console.error("Error updating appointment:", data.error || "Unknown error");
                return false;
            }
        })
        .catch(err => {
            console.error("Error in API call:", err);
            return false;
        });
}

function testGetAppointment(appointmentId) {
    if (!appointmentId) {
        console.error("No appointment ID provided for get test");
        return Promise.reject("No appointment ID");
    }

    console.log("Testing get appointment with ID:", appointmentId);

    return fetch(`api/appointments.php?id=${appointmentId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
        credentials: 'same-origin'
    })
        .then(response => {
            console.log("Response status:", response.status);
            return response.json();
        })
        .then(data => {
            console.log("Get appointment response:", data);
            return data;
        })
        .catch(err => {
            console.error("Error in API call:", err);
            return null;
        });
}

function testDeleteAppointment(appointmentId) {
    if (!appointmentId) {
        console.error("No appointment ID provided for delete test");
        return Promise.reject("No appointment ID");
    }

    console.log("Testing delete appointment with ID:", appointmentId);

    return fetch(`api/appointments.php?id=${appointmentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        },
        credentials: 'same-origin'
    })
        .then(response => {
            console.log("Response status:", response.status);
            return response.json();
        })
        .then(data => {
            console.log("Delete appointment response:", data);
            if (data.success) {
                console.log("Success! Deleted appointment ID:", appointmentId);
                return true;
            } else {
                console.error("Error deleting appointment:", data.error || "Unknown error");
                return false;
            }
        })
        .catch(err => {
            console.error("Error in API call:", err);
            return false;
        });
}

function runFullTest() {
    return testCreateAppointment()
        .then(appointmentId => {
            if (appointmentId) {
                console.log("✓ Create appointment test passed");
                return testGetAppointment(appointmentId)
                    .then(appointment => {
                        if (appointment) {
                            console.log("✓ Get appointment test passed");
                            return testUpdateAppointment(appointmentId)
                                .then(updateResult => {
                                    if (updateResult) {
                                        console.log("✓ Update appointment test passed");
                                        return testDeleteAppointment(appointmentId);
                                    }
                                    return null;
                                });
                        }
                        return null;
                    });
            }
            return null;
        })
        .then(deleteResult => {
            if (deleteResult) {
                console.log("✓ Delete appointment test passed");
                console.log("All tests passed successfully!");
            } else {
                console.log("❌ Some tests failed!");
            }
        });
}

// To run the tests, call:
// runFullTest();
