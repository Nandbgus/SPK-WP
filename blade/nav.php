<?php
  // kalau $page belum diset, biarkan kosong supaya tidak error
  $currentPage = $page ?? '';
?>

<div class="row mt-3 justify-content-center">
  <div class="col-lg-10">
    <div class="card border-0 shadow-lg rounded-5 overflow-hidden nav-card-glass">
      <nav class="navbar navbar-expand-lg bg-white">
        <div class="container justify-content-center">

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse justify-content-center" id="navbarNavAltMarkup">
            <ul class="navbar-nav text-center small"> 
              <li class="nav-item">
                <a class="nav-link px-3 fw-semibold nav-pill 
                  <?php echo $currentPage === 'home' ? 'active' : ''; ?>" 
                  href="../home/home.php">
                  Home
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link px-3 fw-semibold nav-pill 
                  <?php echo $currentPage === 'alternatif' ? 'active' : ''; ?>" 
                  href="../alternatif/alternatifView.php">
                  Alternatif
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link px-3 fw-semibold nav-pill 
                  <?php echo $currentPage === 'kriteria' ? 'active' : ''; ?>" 
                  href="../kriteria/kriteriaView.php">
                  Kriteria
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link px-3 fw-semibold nav-pill 
                  <?php echo $currentPage === 'subkriteria' ? 'active' : ''; ?>" 
                  href="../subKriteria/subKriteriaView.php">
                  Sub-Kriteria
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link px-3 fw-semibold nav-pill 
                  <?php echo $currentPage === 'faktor' ? 'active' : ''; ?>" 
                  href="../faktor/faktorView.php">
                  Faktor
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link px-3 fw-semibold nav-pill 
                  <?php echo $currentPage === 'ranking' ? 'active' : ''; ?>" 
                  href="../ranking/ranking.php">
                  Ranking
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link px-3 fw-semibold nav-pill text-danger" 
                  href="../login/userLogout.php"
                  onclick="confirmLogout(event)">
                  Keluar
                </a>
              </li>
            </ul>
          </div>

        </div>
      </nav>
    </div>
  </div>
</div>

<script>
  function confirmLogout(event) {
    event.preventDefault(); 

    const yakin = confirm("Apakah Anda yakin ingin keluar dari sistem?");
    if (yakin) {
      window.location.href = "../login/userLogout.php";
    }
  }
</script>


<style>
  .nav-card-glass {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 40px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
  }

  .navbar {
    padding-top: 0.3rem;
    padding-bottom: 0.3rem;
  }

  .navbar-nav .nav-link {
    border-radius: 999px;
    font-size: 0.9rem;
    transition: all 0.25s ease;
    color: #0066ff;
  }

  .navbar-nav .nav-link.nav-pill {
    padding-top: 0.4rem;
    padding-bottom: 0.4rem;
    margin: 0 4px;
  }

  .navbar-nav .nav-link.nav-pill:hover {
    background-color: #0066ff;
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(0, 102, 255, 0.35);
    transform: translateY(-1px);
  }

  .navbar-nav .nav-link.nav-pill.active {
    background-color: #0066ff;
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(0, 102, 255, 0.35);
  }

  .navbar-nav .nav-link.text-danger {
    color: #dc3545 !important;
  }
  .navbar-nav .nav-link.text-danger:hover {
    background-color: #dc3545;
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.35);
  }

  @media (max-width: 991px) {
    .navbar-nav .nav-link.nav-pill {
      display: block;
      margin: 5px 0;
      font-size: 0.95rem;
    }
  }
</style>
