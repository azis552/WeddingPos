</div>
</main>
<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Azis Koding 2025</div>
            <div>
                <a href="#">Privacy Policy</a>
                &middot;
                <a href="#">Terms &amp; Conditions</a>
            </div>
        </div>
    </div>
</footer>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
</script>
<script src="{{ asset('') }}js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('') }}assets/demo/chart-area-demo.js"></script>
<script src="{{ asset('') }}assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.js" crossorigin="anonymous"></script>
<script src="{{ asset('') }}js/datatables-simple-demo.js"></script>
<script>
    function formatRupiah(angka, prefix = "Rp ") {
        let number_string = angka.toString().replace(/[^,\d]/g, ""),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
        return prefix + rupiah;
    }
</script>

<script>
    $(document).ready(function () {
        function loadNotifications(updateBadge = true) {
            $.ajax({
                url: "/notifications",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    let $notificationList = $("#notification-list");
                    let $notificationBadge = $("#notification-count");

                    $notificationList.empty();

                    if (response.count > 0) {
                        response.notifications.forEach(function (notif) {
                            $notificationList.append(`<li><a class="dropdown-item" href="#">${notif.notifikasi}</a></li>`);
                        });

                        // Jika updateBadge = true (saat halaman load atau dropdown dibuka)
                        if (updateBadge) {
                            $notificationBadge.text(response.count).show();
                        }
                    } else {
                        $notificationList.append('<li class="dropdown-item text-muted">Tidak ada notifikasi</li>');
                        $notificationBadge.hide();
                    }
                },
                error: function () {
                    $("#notification-list").html('<li class="dropdown-item text-danger">Gagal memuat notifikasi</li>');
                }
            });
        }

        // Panggil fungsi loadNotifications() saat halaman dimuat
        loadNotifications(true);

        // Memuat notifikasi saat dropdown diklik
        $("#navbarDropdown").on("click", function () {
            loadNotifications(false); // Tidak perlu update badge lagi

            $.post("/notifications/read", {_token: "{{ csrf_token() }}"}, function () {
                $("#notification-count").hide();
            });
        });
    });
</script>
</body>

</html>
