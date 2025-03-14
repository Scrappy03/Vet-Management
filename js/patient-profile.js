document.addEventListener('DOMContentLoaded', function () {
    // Initialize the edit profile modal
    const editProfileBtn = document.getElementById('editProfileBtn');
    const editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
    const saveChangesBtn = document.getElementById('saveProfileChangesBtn');

    // Show the modal when edit button is clicked
    editProfileBtn.addEventListener('click', function () {
        editProfileModal.show();
    });

    // Handle saving the changes
    saveChangesBtn.addEventListener('click', function () {
        // Get form values
        const petName = document.getElementById('petName').value;
        const petStatus = document.getElementById('petStatus').value;
        const petSpecies = document.getElementById('petSpecies').value;
        const petBreed = document.getElementById('petBreed').value;
        const petGender = document.getElementById('petGender').value;
        const petNeutered = document.getElementById('petNeutered').value;
        const petDob = document.getElementById('petDob').value;
        const petWeight = document.getElementById('petWeight').value;
        const petMicrochip = document.getElementById('petMicrochip').value;
        const petAllergies = document.getElementById('petAllergies').value;
        const ownerName = document.getElementById('ownerName').value;
        const ownerPhone = document.getElementById('ownerPhone').value;
        const ownerEmail = document.getElementById('ownerEmail').value;

        // Format the date of birth
        const dobDate = new Date(petDob);
        const formattedDob = dobDate.toLocaleDateString('en-US', {
            month: 'long',
            day: 'numeric',
            year: 'numeric'
        });

        // Calculate age
        const today = new Date();
        let age = today.getFullYear() - dobDate.getFullYear();
        if (today.getMonth() < dobDate.getMonth() ||
            (today.getMonth() === dobDate.getMonth() && today.getDate() < dobDate.getDate())) {
            age--;
        }

        // Update page with new information
        // Update pet name and status badge
        const profileName = document.getElementById('profileName');
        profileName.innerHTML = `${petName} <span class="badge ${petStatus === 'active' ? 'bg-success' : 'bg-secondary'} fs-6 align-middle">${petStatus === 'active' ? 'Active' : 'Inactive'}</span>`;

        // Update pet details
        const profileDetails = document.getElementById('profileDetails');
        profileDetails.textContent = `${petBreed} • ${petGender.charAt(0).toUpperCase() + petGender.slice(1)} • ${age} years old`;

        // Update owner information
        const profileOwner = document.getElementById('profileOwner');
        const profilePhone = document.getElementById('profilePhone');
        const profileEmail = document.getElementById('profileEmail');

        profileOwner.textContent = ownerName;
        profilePhone.textContent = ownerPhone;
        profileEmail.textContent = ownerEmail;

        // Update allergies display
        const profileAllergies = document.getElementById('profileAllergies');
        profileAllergies.textContent = petAllergies;

        // Update basic information card
        document.getElementById('infoSpecies').textContent = petSpecies;
        document.getElementById('infoBreed').textContent = petBreed;
        document.getElementById('infoDob').textContent = `${formattedDob} (${age} years)`;

        const neuteredText = petNeutered === 'yes' ? 'Neutered' : 'Not Neutered';
        document.getElementById('infoGender').textContent = `${petGender.charAt(0).toUpperCase() + petGender.slice(1)} (${neuteredText})`;
        document.getElementById('infoWeight').textContent = `${petWeight}kg (${new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })})`;
        document.getElementById('infoMicrochip').textContent = petMicrochip;

        // Update avatar initial if name changes
        const avatarInitial = document.getElementById('avatarInitial');
        avatarInitial.textContent = petName.charAt(0);

        // Close the modal
        editProfileModal.hide();

        // Show a success message
        const successToast = new bootstrap.Toast(document.getElementById('saveSuccessToast'));
        successToast.show();
    });
});
