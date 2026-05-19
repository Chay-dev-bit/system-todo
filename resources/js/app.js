import Swal from 'sweetalert2';
import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.css";
window.locationClock = function () {

    return {

        // ambil lokasi tersimpan
        location: localStorage.getItem('location') || '',

        date: '',

        async getLocation() {

            this.updateDate();

            // jika browser support GPS
            if (navigator.geolocation) {

                navigator.geolocation.getCurrentPosition(

                    async (position) => {

                        let lat = position.coords.latitude;
                        let lon = position.coords.longitude;

                        try {

                            let response = await fetch(
                                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`
                            );

                            let data = await response.json();

                            // ambil nama kota
                            this.location =
                                data.address.city ||
                                data.address.town ||
                                data.address.county ||
                                'Lokasi tidak diketahui';

                            // simpan ke localStorage
                            localStorage.setItem(
                                'location',
                                this.location
                            );

                        } catch (e) {

                            this.location =
                                'Lokasi gagal dibaca';

                        }

                    },

                    () => {

                        this.location =
                            'Izin lokasi ditolak';

                    }

                );

            }

        },

        updateDate() {

            let now = new Date();

            this.date = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

        }

    }

}

// untuk confrim delete
document.addEventListener('livewire:init', () => {

    Livewire.on('show-delete-confirmation', (event) => {

        Swal.fire({
            title: 'Yakin?',
            text: 'Data yang dihapus tidak bisa dikembalikan!',
            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',

            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',

            reverseButtons: true
        }).then((result) => {

            if (result.isConfirmed) {

                Livewire.dispatch('deleteConfirmed', {
                    id: event.id
                });

            }

        });

    });

});

// unutk confrim logout
document.getElementById('logout').addEventListener('click', function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Anda Yakin Logout?',
        text: 'Anda akan keluar dari sistem',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Logout!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    })
});

// untuk select
document.addEventListener('livewire:init', () => {

    document.querySelectorAll('.tom-select').forEach((el) => {

        if (!el.tomselect) {

            new TomSelect(el, {

                create: false,

                sortField: {
                    field: "text",
                    direction: "asc"
                },

                placeholder: "Cari Data..."

            });

        }

    });

});