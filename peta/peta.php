

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta</title>
    <link rel="icon" href="../images/map.png" type="image/x-icon">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Poppins:ital,wght@0,400;1,400&display=swap"
        rel="stylesheet">
</head>

<body>
    <header>
        <nav class="nav-container">
            <a href="../index.php">
                <div class="logo-section">
                    <img src="../images/logo.jpg" alt="Logo" class="logo-img">
                </div>
            </a>
            <div class="nav-links">
                <a class="nav-link " href="../index.php">Beranda</a>
                <a class="nav-link active" href="peta.php">Peta</a>
            </div>
        </nav>
    </header>

</body>

</html>
    <div id="search-container">
        <input type="text" id="searchInput" placeholder="Cari Gedung" />
    </div>

    <div id="map"></div>

    <div id="filterContainer" class="leaflet-control">
        <button id="filterButton">Filter</button>
        <div id="filterMenu" style="display: none;">
            <h3>Filter Berdasarkan:</h3>
            <label for="departmentFilter">Jurusan:</label>
            <select id="departmentFilter">
                <!-- Opsi diisi menggunakan JavaScript -->
            </select>
            <br>
            <label for="buildingFilter">Fungsi Gedung:</label>
            <select id="buildingFilter">
                <option value="all">Semua</option>
                <option value="Kantor">Kantor</option>
                <option value="Gedung Jurusan">Gedung Jurusan</option>
                <option value="Gedung Teori">Gedung Teori</option>
                <option value="Laboratorium">Laboratorium</option>
                <option value="Bengkel">Bengkel</option>
                <option value="Ibadah">Ibadah</option>
                <option value="Olahraga">Olahraga</option>
                <option value="Ormawa">Organisasi & UKM</option>
                <option value="Perpustakaan">Perpustakaan</option>
                <option value="Kantin">Kantin</option>
            </select>
        </div>
    </div>

    <div id="overlay" class="overlay"></div>

    <div id="sidebar">
        <button id="closeSidebar" onclick="closeSidebar()">&#10006;</button>
        <div class="header-row">
            <h3 id="placeCode"></h3>
            <h3 id="placeName"></h3>
        </div>
        <img id="placePhoto" src="" alt="Photo" style="width: 100%; height: auto;" loading="lazy">
        <p><strong>Deskripsi:</strong> <span id="placeDescription"></span></p>
        <p><strong>Jumlah kelas:</strong> <span id="placeClasses"></span></p>
        <p><strong>Jumlah lantai:</strong> <span id="placeFloors"></span></p>
        <p><strong>Jam Operasional:</strong><br><span id="placeHours"></span></p>
        <p><strong>Website:</strong> <a id="placeWebsite" href="" target="_blank">Visit Website</a></p>
    </div>

    <script>
        const DEPARTMENT_COLORS = {
            'Elektro': '#C11B17',
            'Akuntansi': '#FFFF33',
            'Mesin': '#3D3C3A',
            'Sipil': '#C19A6B',
            'Bisnis': '#12AD2B',
            'Institusi': '#18788f',
            'Tambang': '#F62217',
            'default': '#18788f'
        };

        const DEFAULT_POLYGON_STYLE = {
            weight: 2,
            opacity: 1,
            fillOpacity: 0.70,
            dashArray: '6, 5',
        };

        const HOVER_POLYGON_STYLE = {
            weight: 3,
            opacity: 1,
            fillOpacity: 0.8,
            dashArray: null,
        };

        // Inisialisasi peta
        function initializeMap() {
            var map = L.map('map').setView([-3.295882, 114.582623], 18);
            var osmLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            });

            var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles © Esri & contributors'
            });

            osmLayer.addTo(map);

            // Fitur Layer
            L.control.layers({
                "Mode Biasa (OSM)": osmLayer,
                "Mode Satelit": satelliteLayer
            }).addTo(map);

            const polibanArea = [
                [-3.297124, 114.580868],
                [-3.296878, 114.58118],
                [-3.296273, 114.581373],
                [-3.296214, 114.581518],
                [-3.295807, 114.581378],
                [-3.294918, 114.582065],
                [-3.294564, 114.582108],
                [-3.293847, 114.582609],
                [-3.295097, 114.58413],
                [-3.297938, 114.581786],
                [-3.297938, 114.581786],
                [-3.297938, 114.581786],
                [-3.297124, 114.580868]
            ];

            const polygon = L.polygon(polibanArea, {
                color: '#A68487',
                weight: 5,
                opacity: 1,
                fillColor: 'gray',
                fillOpacity: 0.15
            }).addTo(map);

            return map;
        }

        // Inisialisasi semua lokasi pada peta
        function initializeLocations(map, locations) {
            const polygons = {};

            locations.forEach(location => {
                if (location.location_coords && Array.isArray(location.location_coords[0])) {
                    const polygon = L.polygon(location.location_coords[0],
                        createPolygonStyle(location.department)).addTo(map);

                    polygons[location.department] = polygons[location.department] || [];
                    polygons[location.department].push(polygon);

                    if (location.code) {
                        createPolygonLabel(polygon, location.code);
                    }

                    addPolygonEventListeners(polygon, location, map);
                }
            });

            return polygons;
        }

        function getDepartmentColor(department) {
            return DEPARTMENT_COLORS[department] || DEPARTMENT_COLORS['default'];
        }

        function createPolygonStyle(department, isDefault = true) {
            const color = getDepartmentColor(department);
            return isDefault ? {
                    ...DEFAULT_POLYGON_STYLE,
                    color,
                    fillColor: color
                } :
                HOVER_POLYGON_STYLE;
        }

        // Membuat label untuk poligon
        function createPolygonLabel(polygon, labelContent) {
            return polygon.bindTooltip(labelContent, {
                permanent: true,
                direction: 'center',
                className: 'polygon-label',
                offset: [0, 0],
                opacity: 1
            }).openTooltip();
        }

        function addPolygonEventListeners(polygon, location, map) {
            polygon.on('mouseover', function(e) {
                e.target.setStyle(createPolygonStyle(location.department, false));
                // Tampilkan hanya nama jika code tidak ada atau NULL
                const departmentName = location.department || location.building_function;
                const popupContent = location.code ?
                    `<div class="popup-content">
                <strong>${location.name} (${location.code})</strong><br>
                ${departmentName}<br>
            </div>` :
                    `<div class="popup-content">
                <strong>${location.name}</strong><br>
                ${departmentName}<br>
            </div>`;
                e.target.bindPopup(popupContent).openPopup();
            });

            polygon.on('mouseout', function(e) {
                e.target.setStyle(createPolygonStyle(location.department));
                e.target.closePopup();
            });

            polygon.on('click', function() {
                updateSidebar(location);
                openSidebar();
            });
        }

        // Memperbarui sidebar
        function updateSidebar(location) {
            const placeNameElement = document.getElementById('placeName');
            const placeCode = location.code ? ` (${location.code})` : ''; // Tambahkan kode jika ada
            const placeName = location.name ? location.name + placeCode : ''; // Format nama dan kode

            // Perbarui elemen HTML dengan hasil format
            placeNameElement.textContent = placeName;

            const sidebarElements = {
                'placeClasses': location.num_of_classes,
                'placeFloors': location.num_of_floors,
                'placeDescription': location.description,
                'placeHours': location.hours
            };

            Object.entries(sidebarElements).forEach(([id, value]) => {
                const element = document.getElementById(id);
                if (value) {
                    element.parentElement.style.display = 'block';

                    // Cek jika id adalah 'placeHours' dan kita perlu memformat jam operasional
                    if (id === 'placeHours') {
                        // Pisahkan jam yang ada di dalam value
                        const hoursArray = value.split(' ');
                        const formattedHours = hoursArray.map((part, index) => {
                            // Cek apakah bagian ini adalah jam (misalnya "07.00-18.00")
                            if (part.match(/\d{2}.\d{2}-\d{2}.\d{2}/)) {
                                // Jika ini adalah jam, tambahkan baris baru setelah hari sebelumnya
                                return `${part}<br>`;
                            }
                            // Jika bukan jam, biarkan tetap
                            return part;
                        }).join(' ');
                        // Gabungkan kembali dan set innerHTML agar <br> dapat ditampilkan
                        element.innerHTML = formattedHours;
                    } else {
                        element.textContent = value;
                    }
                } else {
                    element.parentElement.style.display = 'none'; // Sembunyikan elemen jika tidak ada nilai
                }
            });
            document.getElementById('placePhoto').src = location.photo || 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTvX7ghSY75PvK5S-RvhkFxNz88MWEALSBDvA&s'; // Atur default jika tidak ada foto
            document.getElementById('placeWebsite').href = location.website || 'https://davidagusk.github.io/'; // Atur default jika tidak ada website
        }

        function populateDepartmentFilter() {
            const departmentFilter = document.getElementById('departmentFilter');
            departmentFilter.innerHTML = `<option value="all">Semua</option> ${Object.keys(DEPARTMENT_COLORS).filter(k => k !== 'default').map(dept => `<option value="${dept}">${dept}</option>`).join('')}`;
        }

        function createFilterControl(map, locations, polygons) {
            // Panggil fungsi untuk mengisi dropdown
            populateDepartmentFilter();

            var container = document.getElementById('filterContainer');

            document.getElementById('filterButton').addEventListener('click', () => {
                var menu = document.getElementById('filterMenu');
                menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
            });

            document.getElementById('departmentFilter').addEventListener('change', () => {
                filterLocations(map, locations, polygons);
            });

            document.getElementById('buildingFilter').addEventListener('change', () => {
                filterLocations(map, locations, polygons);
            });

            var filterControl = L.Control.extend({
                options: {
                    position: 'topright'
                },
                onAdd: function() {
                    return container;
                }
            });

            map.addControl(new filterControl());
        }

        // Fitur pencarian
        function searchLocations(map, locations, polygons) {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            console.log("Searching for:", searchInput);

            const filteredLocations = locations.filter(location => {
                const name = location.name ? location.name.toLowerCase() : '';
                const department = location.department ? location.department.toLowerCase() : '';
                const buildingFunction = location.building_function ? location.building_function.toLowerCase() : '';
                const code = location.code ? location.code.toLowerCase() : '';

                if (searchInput.startsWith('gedung')) {
                    const searchTerm = searchInput.replace('gedung', '').trim(); // Hapus 'gedung' dari input

                    // Jika input yang dimasukkan hanya dua karakter, prioritaskan pencocokan kode gedung
                    if (searchTerm.length < 3) {
                        return code.includes(searchTerm);
                    }

                    return name.includes(searchTerm);
                }

                return name.includes(searchInput) ||
                    department.includes(searchInput) ||
                    buildingFunction.includes(searchInput) ||
                    code.includes(searchInput);
            });

            // Membersihkan poligon
            Object.values(polygons).flat().forEach(polygon => map.removeLayer(polygon));
            Object.keys(polygons).forEach(key => {
                polygons[key] = [];
            });

            // Tambahkan poligon berdasarkan hasil pencarian
            filteredLocations.forEach(location => {
                if (location.location_coords && Array.isArray(location.location_coords[0])) {
                    const polygon = L.polygon(location.location_coords[0], createPolygonStyle(location.department)).addTo(map);

                    if (!polygons[location.department]) {
                        polygons[location.department] = [];
                    }
                    polygons[location.department].push(polygon);

                    if (location.code) {
                        createPolygonLabel(polygon, location.code);
                    }

                    addPolygonEventListeners(polygon, location, map);
                }
            });

            return polygons;
        }

        // Fungsi tombol filter
        function filterLocations(map, locations, polygons) {
            const selectedDepartment = document.getElementById('departmentFilter').value;
            const selectedBuilding = document.getElementById('buildingFilter').value;

            // Hapus semua poligon dari peta
            Object.values(polygons).flat().forEach(polygon => map.removeLayer(polygon));

            // Reset objek polygons
            Object.keys(polygons).forEach(key => {
                polygons[key] = [];
            });

            locations.forEach(location => {
                if ((selectedDepartment === 'all' || location.department === selectedDepartment) &&
                    (selectedBuilding === 'all' || location.building_function === selectedBuilding)) {

                    if (location.location_coords && Array.isArray(location.location_coords[0])) {
                        const polygon = L.polygon(location.location_coords[0],
                            createPolygonStyle(location.department)).addTo(map);

                        // Tambahkan poligon baru ke array yang sesuai
                        if (!polygons[location.department]) {
                            polygons[location.department] = [];
                        }
                        polygons[location.department].push(polygon);

                        if (location.code) {
                            createPolygonLabel(polygon, location.code);
                        }

                        addPolygonEventListeners(polygon, location, map);
                    }
                }
            });

            return polygons;
        }

        // Fungsi untuk membuka/menutup sidebar
        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('map').style.marginLeft = "300px";
            document.getElementById('overlay').style.display = "block";
            setTimeout(function() {
                const photoElement = document.getElementById('placePhoto');
                if (photoElement && !photoElement.src) {
                    photoElement.src = "../images/Maps.jpg"; // Ganti dengan path foto yang sesuai
                }
            }, 500);
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('map').style.marginLeft = "0";
            document.getElementById('overlay').style.display = "none";
        }
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSidebar();
            }
        });

        // Inisialisasi utama
        function main() {
            const locations = [{"code":"A","name":"Kantor Pusat Poliban","building_function":"Kantor","department":"Institusi","num_of_classes":"17","num_of_floors":"2","hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/gedung_a.jpg","description":"Merupakan gedung Utama di Politeknik Negeri Banjarmasin yang berisi ruang direktur dan sejenisnya serta juga ruang Multimedia yang digunakan untuk acara\/rapat","website":"poliban.ac.id","location_coords":[[[-3.2963436,114.582046],[-3.2964427,114.5821562],[-3.2964199,114.5821761],[-3.2965203,114.5822914],[-3.2965449,114.5822699],[-3.2966582,114.582396],[-3.2968051,114.5822635],[-3.2964905,114.5819135],[-3.2963436,114.582046]]]},{"code":"B","name":"Gedung Jurusan Teknik Sipil","building_function":"Gedung Jurusan","department":"Sipil","num_of_classes":"4","num_of_floors":"2","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_b.jpg","description":"Merupakan salah satu gedung jurusan untuk Teknik Sipil dan Kebumian yang berisi ruang dosen dan admin. Juga memiliki loket jurusan yang difungsikan untuk mahasiswa melakukan urusan administrasi perkuliahan. ","website":"https:\/\/poliban.ac.id\/sipil\/","location_coords":[[[-3.2970066,114.5814793],[-3.2969912,114.5814524],[-3.2969035,114.5815091],[-3.2969259,114.5815424],[-3.296864,114.5815799],[-3.2969201,114.5816783],[-3.2971263,114.5815454],[-3.2970617,114.5814428],[-3.2970066,114.5814793]]]},{"code":"C","name":"Gedung Teori Teknik Mesin","building_function":"Gedung Teori","department":"Mesin","num_of_classes":"13","num_of_floors":"2","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_c.jpg","description":"Merupakan salah satu gedung pelaksanaan kegiatan belajar mengajar teori bagi mahasiswa Jurusan Teknik Mesin.","website":"https:\/\/poliban.ac.id\/mesin\/","location_coords":[[[-3.2969956,114.5819615],[-3.2970161,114.581985],[-3.2969916,114.5820071],[-3.297091,114.5821177],[-3.2971138,114.5820972],[-3.2971357,114.5821223],[-3.2973208,114.5819606],[-3.2971808,114.5817998],[-3.2969956,114.5819615]]]},{"code":"D","name":"Gedung Jurusan Teknik Elektro","building_function":"Gedung Jurusan","department":"Elektro","num_of_classes":"16","num_of_floors":"2","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_d.jpg","description":"Merupakan salah satu gedung jurusan untuk Teknik Elektro yang berisi ruang dosen dan admin serta lab. Juga memiliki loket jurusan yang difungsikan untuk mahasiswa melakukan urusan administrasi perkuliahan.","website":"https:\/\/poliban.ac.id\/elektro\/","location_coords":[[[-3.2968277,114.5815203],[-3.2967267,114.5814792],[-3.2968324,114.5812339],[-3.2969332,114.5812722],[-3.2968277,114.5815203]]]},{"code":"E","name":"Gedung Teori Administrasi Bisnis","building_function":"Gedung Teori","department":"Bisnis","num_of_classes":"15","num_of_floors":"2","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_e.jpg","description":"Merupakan salah satu gedung tempat kegiatan belajar mengajar teori bagi Mahasiswa Jurusan Administrasi Bisnis.","website":"https:\/\/poliban.ac.id\/administrasi-bisnis\/","location_coords":[[[-3.2944484,114.58251],[-3.2942852,114.5826299],[-3.2944619,114.5828713],[-3.2946252,114.5827514],[-3.2944484,114.58251]]]},{"code":"F","name":"Gedung Jurusan Administrasi Bisnis","building_function":"Gedung Jurusan","department":"Bisnis","num_of_classes":"7","num_of_floors":"1","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_f.jpg","description":"Merupakan salah satu gedung jurusan untuk Administrasi Bisnis yang berisi ruang dosen dan admin. Juga memiliki loket jurusan yang difungsikan untuk mahasiswa melakukan urusan administrasi perkuliahan.","website":"https:\/\/poliban.ac.id\/administrasi-bisnis\/","location_coords":[[[-3.294476,114.582277],[-3.294294,114.582385],[-3.294364,114.582508],[-3.294549,114.5824],[-3.294476,114.582277]]]},{"code":"G","name":"Gedung Teori Teknik Sipil","building_function":"Gedung Teori","department":"Sipil","num_of_classes":"16","num_of_floors":"2","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_g.jpg","description":"Merupakan salah satu gedung tempat kegiatan belajar mengajar teori bagi Mahasiswa Jurusan Sipil dan Kebumian.","website":"https:\/\/poliban.ac.id\/sipil\/","location_coords":[[[-3.295986,114.581727],[-3.295844,114.581856],[-3.296018,114.582059],[-3.296162,114.581936],[-3.295986,114.581727]]]},{"code":"H","name":"Gedung Teori Teknik Elektro","building_function":"Gedung Teori","department":"Elektro","num_of_classes":"15","num_of_floors":"2","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_h.jpg","description":"Merupakan salah satu gedung pelaksanaan kegiatan belajar mengajar teori bagi mahasiswa Jurusan Teknik Elektro.","website":"https:\/\/poliban.ac.id\/elektro\/","location_coords":[[[-3.2957451,114.5814478],[-3.2955297,114.5816487],[-3.2956743,114.5818043],[-3.2958897,114.5816034],[-3.2957451,114.5814478]]]},{"code":"I","name":"Bengkel Teknik Sipil","building_function":"Bengkel","department":"Sipil","num_of_classes":"5","num_of_floors":"1","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_i.jpg","description":"Merupakan salah satu gedung tempat kegiatan belajar mengajar bagi Mahasiswa Jurusan Sipil dan Kebumian. Sering disebut juga sebagai bengkel Teknik Sipil dikarenakan menjadi tempat untuk melakukan praktik.","website":"https:\/\/poliban.ac.id\/sipil\/","location_coords":[[[-3.296007,114.582408],[-3.295831,114.58255],[-3.296114,114.58288],[-3.296285,114.582735],[-3.296007,114.582408]]]},{"code":"J","name":"Bengkel Teknik Mesin Produksi","building_function":"Bengkel","department":"Mesin","num_of_classes":"3","num_of_floors":"1","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_j.jpg","description":"Merupakan gedung workshop Prodi Teknik Mesin yang mendukung pelaksanaan kegiatan belajar mengajar bagi mahasiswa Jurusan Teknik Mesin.","website":"https:\/\/poliban.ac.id\/mesin\/","location_coords":[[[-3.295467,114.582333],[-3.295282,114.582494],[-3.29571,114.582971],[-3.295892,114.582818]]]},{"code":"K","name":"Laboratorium Teknik Elektro","building_function":"Laboratorium","department":"Elektro","num_of_classes":"12","num_of_floors":"1","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_k.jpg","description":"Merupakan salah satu gedung laboratorium tempat kegiatan belajar mengajar dan praktek bagi Mahasiswa Jurusan Teknik Elektro","website":"https:\/\/poliban.ac.id\/elektro\/","location_coords":[[[-3.2955148,114.5820487],[-3.2957224,114.5822901],[-3.2958859,114.582149],[-3.2956784,114.5819076],[-3.2955148,114.5820487]]]},{"code":"L","name":"Bengkel Teknik Elektro","building_function":"Bengkel","department":"Elektro","num_of_classes":"7","num_of_floors":"2","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_l.jpg","description":"Merupakan salah satu gedung tempat kegiatan belajar mengajar bagi Mahasiswa Jurusan Teknik Elektro. Sering disebut sebagai bengkel listrik dikarenakan lebih sering digunakan untuk melakukan praktik dan memiliki fasilitas peralatan penunjang kegiatan belajar mengajar.","website":"https:\/\/poliban.ac.id\/elektro\/","location_coords":[[[-3.2954479,114.581708],[-3.2952353,114.5819041],[-3.2953906,114.5820731],[-3.2956032,114.581877],[-3.2954479,114.581708]]]},{"code":"M","name":"Bengkel Teknik Hidrolika Sipil","building_function":"Bengkel","department":"Sipil","num_of_classes":"4","num_of_floors":"1","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_m.jpg","description":"Merupakan salah satu gedung tempat kegiatan belajar mengajar bagi Mahasiswa Jurusan Sipil dan Kebumian.","website":"https:\/\/poliban.ac.id\/sipil\/","location_coords":[[[-3.2951567,114.5819669],[-3.2949978,114.5821051],[-3.2951745,114.5823089],[-3.2953334,114.5821707],[-3.2951567,114.5819669]]]},{"code":"N","name":"Teknik Pertambangan","building_function":null,"department":"Tambang","num_of_classes":"5","num_of_floors":"1","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_n.jpg","description":"Merupakan salah satu gedung tempat kegiatan belajar mengajar bagi Mahasiswa Prodi Teknik Pertambangan sekaligus menjadi tempat uji LSP Teknik Pertambangan","website":null,"location_coords":[[[-3.2949487,114.582483],[-3.2950028,114.5825493],[-3.2951241,114.5824501],[-3.2950701,114.5823838],[-3.2949487,114.582483]]]},{"code":"O","name":"Gedung Jurusan Teknik Otomotif","building_function":"Gedung Jurusan","department":"Mesin","num_of_classes":"5","num_of_floors":"2","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_o.jpg","description":"Merupakan gedung workshop Prodi Teknologi Rekayasa Otomotif yang mendukung pelaksanaan kegiatan belajar mengajar bagi mahasiswa Jurusan Teknik Mesin.","website":"","location_coords":[[[-3.295615,114.5831645],[-3.2956855,114.583249],[-3.2956219,114.5833044],[-3.2956524,114.5833515],[-3.2953828,114.5835841],[-3.2952404,114.5834138],[-3.2955058,114.5831908],[-3.2955431,114.5832282],[-3.295615,114.5831645]]]},{"code":"P","name":"Gedung Teori Teknik Alat Berat","building_function":"Gedung Teori","department":"Mesin","num_of_classes":"4","num_of_floors":"2","hours":"Senin-Jumat        07.00-17.00","photo":"..\/images\/gedung_p.jpg","description":"Merupakan gedung workshop Prodi Alat Berat yang mendukung pelaksanaan kegiatan belajar mengajar bagi mahasiswa Jurusan Teknik Mesin.","website":"https:\/\/poliban.ac.id\/mesin\/","location_coords":[[[-3.2946861,114.5823363],[-3.2945394,114.5824538],[-3.2947322,114.5826952],[-3.2948789,114.5825777],[-3.2946861,114.5823363]]]},{"code":"Q","name":"Gedung Serba Guna","building_function":null,"department":"Institusi","num_of_classes":"4","num_of_floors":"1","hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/gedung_q.jpg","description":"Merupakan gedung Serbaguna di Politeknik Negeri Banjarmasin yang biasa digunakan untuk mengadakan acara besar kampus maupun himpunan atau komunitas.","website":null,"location_coords":[[[-3.2945627,114.5831617],[-3.2948084,114.5834614],[-3.2949804,114.5833199],[-3.2948875,114.5832065],[-3.2949418,114.5831618],[-3.2948767,114.5830824],[-3.2948224,114.5831271],[-3.2947347,114.5830202],[-3.2945627,114.5831617]]]},{"code":"R","name":"Gedung P3M","building_function":"Kantor","department":"Institusi","num_of_classes":"2","num_of_floors":"1","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_r.jpg","description":"Merupakan gedung yang dimanfaatkan untuk melaksanakan Penelitian dan Pengabdian Kepada Masyarakat sebagai bagian dari Tri Dharma Perguruan Tinggi.","website":"https:\/\/poliban.ac.id\/pusat-p3mp\/","location_coords":[[[-3.2961361,114.5817214],[-3.2961977,114.5817898],[-3.2963112,114.5816872],[-3.2962496,114.5816188],[-3.2961361,114.5817214]]]},{"code":"S","name":"UPT PP, KOPERASI, LAB ALKS, GUDANG, KOMPUTER BISNIS","building_function":null,"department":"Akuntansi","num_of_classes":"5","num_of_floors":"1","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_s.jpg","description":"Merupakan salah satu gedung jurusan untuk Akuntansi yang berisi ruang dosen serta lab.","website":"https:\/\/poliban.ac.id\/akuntansi\/","location_coords":[[[-3.296421,114.581563],[-3.296356,114.58163],[-3.296531,114.581833],[-3.296609,114.581766],[-3.296421,114.581563]]]},{"code":"T","name":"UPT Lab Bahasa","building_function":"Kantor","department":"Institusi","num_of_classes":"7","num_of_floors":"2","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_t.jpg","description":"Unit pelayanan terpadu yang menyediakan fasilitas laboratorium bahasa untuk mendukung pembelajaran bahasa bagi mahasiswa","website":"https:\/\/poliban.ac.id\/unit-bahasa\/","location_coords":[[[-3.2972146,114.5810765],[-3.2971318,114.5811473],[-3.2972027,114.5812305],[-3.2971179,114.581303],[-3.2971695,114.5813635],[-3.2973371,114.5812203],[-3.2972146,114.5810765]]]},{"code":"U","name":"Gedung Jurusan Teknik Mesin","building_function":"Gedung Jurusan","department":"Mesin","num_of_classes":"4","num_of_floors":"2","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_u.jpg","description":"Merupakan salah satu gedung jurusan untuk Teknik Mesin yang berisi ruang dosen dan admin serta lab. Juga memiliki loket jurusan yang difungsikan untuk mahasiswa melakukan urusan administrasi perkuliahan.","website":"https:\/\/poliban.ac.id\/mesin\/","location_coords":[[[-3.297113,114.5809878],[-3.2969762,114.581189],[-3.2970545,114.581238],[-3.2971926,114.5810368],[-3.297113,114.5809878]]]},{"code":"V","name":"Bengkel Otomotif Modifikasi","building_function":"Bengkel","department":"Mesin","num_of_classes":"1","num_of_floors":"2","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_v.jpg","description":"Merupakan gedung praktikum sekaligus teori dari jurusan Teknik Mesin.","website":"https:\/\/poliban.ac.id\/mesin\/","location_coords":[[[-3.295009,114.5835639],[-3.2950901,114.5836593],[-3.2950288,114.5837116],[-3.2950834,114.5837758],[-3.2952965,114.5835938],[-3.2951608,114.5834343],[-3.295009,114.5835639]]]},{"code":"W","name":"Gedung Jurusan Akuntansi","building_function":"Gedung Jurusan","department":"Akuntansi","num_of_classes":"11","num_of_floors":"3","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_w.jpg","description":"Merupakan gedung Jurusan Akuntansi yang berisi ruang dosen serta lab.","website":"https:\/\/poliban.ac.id\/akuntansi\/","location_coords":[[[-3.2944658,114.5829197],[-3.2945594,114.5830532],[-3.2947348,114.5829298],[-3.2946412,114.5827963],[-3.2944658,114.5829197]]]},{"code":"X","name":"UPT TIK","building_function":null,"department":"Institusi","num_of_classes":"14","num_of_floors":"3","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedung_x.jpg","description":"Merupakan gedung Unit Penunjang Akademik Teknologi Informasi dan Komunikasi di Politeknik Negeri Banjarmasin yang memiliki lab-lab.","website":"https:\/\/poliban.ac.id\/upt-tik\/","location_coords":[[[-3.2953832,114.5821849],[-3.2956121,114.5824444],[-3.2957332,114.5823351],[-3.2955042,114.5820757],[-3.2953832,114.5821849]]]},{"code":"Y","name":"Perpustakaan","building_function":null,"department":"Institusi","num_of_classes":null,"num_of_floors":"2","hours":"Senin-Jum'at        08.30-15.30","photo":"..\/images\/gedung_y.jpg","description":"Merupakan gedung Perpustakaan di Politeknik Negeri Banjarmasin yang biasa digunakan untuk mencari referensi buku ataupun laporan TA.","website":"http:\/\/perpustakaan.poliban.ac.id\/","location_coords":[[[-3.2963061,114.5817514],[-3.296285,114.5817296],[-3.2962087,114.5818036],[-3.296222,114.5818174],[-3.2961891,114.5818493],[-3.2962437,114.5819058],[-3.2962758,114.5818747],[-3.296308,114.581908],[-3.2963844,114.581834],[-3.2963555,114.581804],[-3.2963776,114.5817826],[-3.2963274,114.5817308],[-3.2963061,114.5817514]]]},{"code":"Z","name":"Gedung UKM","building_function":"Ormawa","department":null,"num_of_classes":"8","num_of_floors":"1","hours":"Senin-Jum'at        07.00-18.00\r\nSabtu-Minggu      07.00-18.00","photo":"..\/images\/gedung_z.jpg","description":"Merupakan sebuah sekretariat UKM Poliban yang digunakan untuk melakukan kegiatan yang berhubungan seperti rapat dan lainnya.","website":null,"location_coords":[[[-3.2950122,114.583598],[-3.294865,114.5837358],[-3.2950355,114.583941],[-3.2950744,114.583908],[-3.2949531,114.5837613],[-3.2950639,114.5836594],[-3.2950122,114.583598]]]},{"code":"","name":"Gedung Baru Administrasi Bisnis","building_function":null,"department":"Bisnis","num_of_classes":"8","num_of_floors":"2","hours":"Senin-Jum'at        07.00-17.00","photo":"..\/images\/gedungbaru.jpg","description":"Merupakan salah satu gedung tempat kegiatan belajar mengajar teori sekaligus praktik bagi Mahasiswa Jurusan Administrasi Bisnis.","website":"https:\/\/poliban.ac.id\/administrasi-bisnis\/","location_coords":[[[-3.2944821,114.5822199],[-3.294583,114.5823482],[-3.2946767,114.5822784],[-3.2945785,114.5821501],[-3.2944821,114.5822199]]]},{"code":"","name":"Parkiran Dosen Mesin","building_function":"Parkiran","department":"Mesin","num_of_classes":null,"num_of_floors":null,"hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/parkiran_dosenmesin.jpg","description":"Area parkir khusus yang disediakan bagi dosen dan staf kampus Politeknik Negeri Banjarmasin.","website":"https:\/\/poliban.ac.id\/mesin\/","location_coords":[[[-3.296973,114.58119],[-3.296932,114.581273],[-3.296994,114.581321],[-3.297056,114.581235],[-3.296973,114.58119]]]},{"code":"","name":"Parkiran Elektro","building_function":"Parkiran","department":"Elektro","num_of_classes":null,"num_of_floors":null,"hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/parkiran_elektro.jpg","description":"Area parkir yang disediakan bagi mahasiswa jurusan teknik","website":"https:\/\/poliban.ac.id\/elektro\/","location_coords":[[[-3.297651,114.581724],[-3.296717,114.582547],[-3.296799,114.582644],[-3.297738,114.581821],[-3.297651,114.581724]]]},{"code":"","name":"Parkiran Dosen Elektro","building_function":"Parkiran","department":"Elektro","num_of_classes":null,"num_of_floors":null,"hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/parkiran_dosenelektro.jpg","description":"Area parkir khusus yang disediakan bagi dosen dan staf kampus Politeknik Negeri Banjarmasin.","website":"https:\/\/poliban.ac.id\/elektro\/","location_coords":[[[-3.2970211,114.5813875],[-3.2970617,114.5814428],[-3.2971527,114.5813757],[-3.2971121,114.5813204],[-3.2970211,114.5813875]]]},{"code":null,"name":"Parkiran GSG depan","building_function":"Parkiran","department":null,"num_of_classes":null,"num_of_floors":null,"hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/parkiran_gsgd.jpg","description":"Area parkir yang terleletak di depan Gedung Serba Guna, biasa digunakan sebagai tempat parkir saat ada kegiatan berlangsung","website":null,"location_coords":[[[-3.295354,114.582847],[-3.294962,114.583195],[-3.295085,114.583335],[-3.295474,114.582987],[-3.295354,114.582847]]]},{"code":null,"name":"Parkiran GSG belakang","building_function":"Parkiran","department":null,"num_of_classes":null,"num_of_floors":null,"hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/parkiran_gsgb.jpg","description":"Area parkir yang terleletak di belakang Gedung Serba Guna, biasa digunakan sebagai tempat parkir saat ada kegiatan berlangsung","website":null,"location_coords":[[[-3.294533,114.583226],[-3.294488,114.583263],[-3.294734,114.583561],[-3.294785,114.583515],[-3.294533,114.583226]]]},{"code":null,"name":"Parkiran GSG samping","building_function":"Parkiran","department":null,"num_of_classes":null,"num_of_floors":null,"hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/parkiran_gsgs.jpg","description":"Area parkir yang terleletak di samping Gedung Serba Guna, biasa digunakan sebagai tempat parkir saat ada kegiatan berlangsung","website":null,"location_coords":[[[-3.294997,114.583389],[-3.29479,114.583563],[-3.294833,114.583617],[-3.295039,114.58344],[-3.294997,114.583389]]]},{"code":null,"name":"Parkiran Bisnis","building_function":"Parkiran","department":"Bisnis","num_of_classes":null,"num_of_floors":null,"hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/parkiran_akuntansi.jpg","description":"Area parkir yang disediakan bagi mahasiswa jurusan Administrasi Bisnis dan Akuntansi","website":null,"location_coords":[[[-3.2962373,114.5829485],[-3.2963272,114.5830477],[-3.2958294,114.5834725],[-3.2957395,114.583364],[-3.2962373,114.5829485]]]},{"code":null,"name":"Parkiran UKM","building_function":"Ormawa","department":null,"num_of_classes":null,"num_of_floors":null,"hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/parkiran_gedungz.jpg","description":"Area parkir khusus yang disediakan bagi Mahasiswa\/i UKM Politeknik Negeri Banjarmasin.","website":null,"location_coords":[[[-3.2949834,114.5837622],[-3.2950945,114.583892],[-3.2953467,114.5836779],[-3.2953202,114.5836461],[-3.2951041,114.5838299],[-3.2950174,114.5837318],[-3.2949834,114.5837622]]]},{"code":null,"name":"Parkiran 1 Gedung Pusat","building_function":"Parkiran","department":null,"num_of_classes":null,"num_of_floors":null,"hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/parkiran_akademik1.jpg","description":"Area parkir khusus yang disediakan bagi staf kampus Politeknik Negeri Banjarmasin.","website":null,"location_coords":[[[-3.2967836,114.5820099],[-3.2968711,114.5821134],[-3.2969003,114.5820886],[-3.2968127,114.5819852],[-3.2967836,114.5820099]]]},{"code":null,"name":"Parkiran 2 Gedung Pusat","building_function":"Parkiran","department":null,"num_of_classes":null,"num_of_floors":null,"hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/parkiran_akademik1.jpg","description":"Area parkir khusus yang disediakan bagi staf kampus Politeknik Negeri Banjarmasin.","website":null,"location_coords":[[[-3.296751,114.582038],[-3.296718,114.582067],[-3.296809,114.582172],[-3.296847,114.582139],[-3.296751,114.582038]]]},{"code":null,"name":"Teaching Factory","building_function":null,"department":"Institusi","num_of_classes":"3","num_of_floors":"1","hours":"Senin-Jum'at        Tutup Sabtu-Minggu      Tutup","photo":"..\/images\/teaching_factory.jpg","description":"Merupakan pengembangan dari unit produksi di sekolah vokasi, yang dirancang untuk mempersiapkan lulusan agar menjadi pekerja dan wirausaha.","website":null,"location_coords":[[[-3.2968062,114.5817985],[-3.2968738,114.5818709],[-3.2969049,114.5818418],[-3.2968373,114.5817694],[-3.2968062,114.5817985]]]},{"code":null,"name":"ETU","building_function":"Kantin","department":null,"num_of_classes":null,"num_of_floors":"1","hours":"Senin-Jum'at        08.30-15.30","photo":"..\/images\/etu.jpg","description":"ETU adalah tempat berkumpulnya mahasiswa untuk menikmati makanan, minuman, dan bersosialisasi selama jam istirahat.","website":"https:\/\/poliban.ac.id\/etu-poliban\/","location_coords":[[[-3.2965361,114.5818341],[-3.2965743,114.5818798],[-3.2966522,114.5818171],[-3.2966075,114.5817687],[-3.2965361,114.5818341]]]},{"code":null,"name":"Kantin","building_function":"Kantin","department":null,"num_of_classes":null,"num_of_floors":"1","hours":"Senin-Jum'at        07.00-16.00","photo":"..\/images\/kantin.jpg","description":"Kantin kampus adalah tempat berkumpulnya mahasiswa untuk menikmati makanan, minuman, dan bersosialisasi selama jam istirahat","website":null,"location_coords":[[[-3.294917,114.5823028],[-3.2948806,114.5823326],[-3.2949733,114.5824463],[-3.2950097,114.5824165],[-3.294917,114.5823028]]]},{"code":null,"name":"Musholla","building_function":"Ibadah","department":null,"num_of_classes":"2","num_of_floors":"2","hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/musholla.jpg","description":"Tempat ibadah yang disediakan bagi mahasiswa dan staf kampus untuk melaksanakan shalat dan kegiatan keagamaan lainnya.","website":null,"location_coords":[[[-3.2962626,114.5814271],[-3.29633,114.5815861],[-3.2964303,114.5815434],[-3.2963628,114.5813844],[-3.2962626,114.5814271]]]},{"code":null,"name":"LSP","building_function":"Kantor","department":"Institusi","num_of_classes":"4","num_of_floors":"1","hours":"Senin-Jum'at        09.00-15.00","photo":"..\/images\/lsp.jpg","description":"Lembaga Sertifikasi Profesi (LSP) merupakan gedung tempat dilakukannya uji kompetensi untuk membantu mahasiswa memperoleh sertifikasi keahlian di Politeknik Negeri Banjarmasin.","website":"https:\/\/lsp.poliban.ac.id\/","location_coords":[[[-3.2959031,114.5822684],[-3.2959758,114.5823522],[-3.2958941,114.5824233],[-3.2958214,114.5823394],[-3.2959031,114.5822684]]]},{"code":null,"name":"Gedung Olahraga","building_function":"Olahraga","department":null,"num_of_classes":null,"num_of_floors":"1","hours":"Senin-Jum'at        Tutup\r\nSabtu-Minggu      Tutup","photo":"..\/images\/gedung_or.jpg","description":"Tempat sarana olahraga yang disediakan bagi mahasiswa dan staf kampus untuk melaksanakan kegiatan olahraga.","website":null,"location_coords":[[[-3.2973571,114.581645],[-3.297207,114.5817717],[-3.2973503,114.581942],[-3.2975004,114.5818153],[-3.2973571,114.581645]]]},{"code":null,"name":"Lapangan Basket","building_function":"Olahraga","department":null,"num_of_classes":null,"num_of_floors":"1","hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/lapangan_basket.jpg","description":"Ruang terbuka yang digunakan untuk bermain basket, selain itu bisa digunakan untuk melaksanakan beberapa kegiatan","website":null,"location_coords":[[[-3.296314,114.582252],[-3.296198,114.582351],[-3.296365,114.582549],[-3.29648,114.58245],[-3.296314,114.582252]]]},{"code":null,"name":"Pos Satpam","building_function":null,"department":"Institusi","num_of_classes":"1","num_of_floors":"1","hours":"Senin-Minggu        24 jam","photo":"..\/images\/pos_satpam.jpg","description":"Tempat petugas keamanan kampus yang siap membantu menjaga keamanan dan memberikan informasi bagi pengunjung di Politeknik Negeri Banjarmasin.","website":"poliban.ac.id","location_coords":[[[-3.2964457,114.5826155],[-3.2964679,114.5826409],[-3.2964403,114.582665],[-3.2964168,114.5826402],[-3.2964457,114.5826155]]]},{"code":null,"name":"Gudang 2","building_function":null,"department":"Sipil","num_of_classes":"3","num_of_floors":"1","hours":"Tutup","photo":"..\/images\/gudang2.jpg","description":"Merupakan tempat untuk menyimpan peralatan-peralatan praktikum.","website":"https:\/\/poliban.ac.id\/sipil\/","location_coords":[[[-3.2952337,114.5823477],[-3.2952805,114.5824065],[-3.295413,114.5823005],[-3.2953662,114.5822418],[-3.2952337,114.5823477]]]},{"code":null,"name":"Ruang Genset","building_function":null,"department":"Institusi","num_of_classes":"2","num_of_floors":"1","hours":"Tutup","photo":"..\/images\/ruang_genset.jpg","description":"Merupakan ruangan yang menghasilkan daya listrik alternatif ketika pasokan listrik dari pembangkit listrik umum mati atau saat diperlukan tambahan pasokan listrik di wilayah tertentu.","website":null,"location_coords":[[[-3.2958121,114.5830578],[-3.2958844,114.583141],[-3.2959322,114.5830992],[-3.2958599,114.5830161],[-3.2958121,114.5830578]]]},{"code":null,"name":"Sekretariat Pramuka","building_function":"Ormawa","department":null,"num_of_classes":"1","num_of_floors":"1","hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/sekre_pramuka.jpg","description":"Merupakan sebuah sekretariat untuk Racana Pramuka Poliban yang digunakan untuk melakukan kegiatan yang berhubungan dengan Racana Pramuka seperti rapat dan lainnya.","website":"https:\/\/linktr.ee\/racana_poliban","location_coords":[[[-3.2972629,114.5816542],[-3.297216,114.5816947],[-3.297245,114.5817249],[-3.2972994,114.5816812],[-3.2972629,114.5816542]]]},{"code":null,"name":"Sekretariat LPM","building_function":"Ormawa","department":null,"num_of_classes":"1","num_of_floors":"1","hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/sekre_lpm.jpg","description":"Merupakan sebuah sekretariat untuk LPM Lensa Poliban yang digunakan untuk melakukan kegiatan yang berhubungan dengan LPM Lensa seperti rapat dan lainnya.","website":"https:\/\/www.lpmlensa.info\/","location_coords":[[[-3.296426,114.581906],[-3.296383,114.581949],[-3.296408,114.581978],[-3.296456,114.581938],[-3.296426,114.581906]]]},{"code":null,"name":"Sekretariat KSR","building_function":"Ormawa","department":null,"num_of_classes":"2","num_of_floors":"1","hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/sekre_ksr.jpg","description":"Merupakan sebuah sekretariat untuk KSR PMI Unit Poliban yang digunakan untuk melakukan kegiatan yang berhubungan dengan KSR PMI seperti rapat dan lainnya.","website":"https:\/\/linktr.ee\/ksrpoliban_","location_coords":[[[-3.2969192,114.5818527],[-3.2969947,114.5819375],[-3.2969655,114.5819656],[-3.2968899,114.581882],[-3.2969192,114.5818527]]]},{"code":null,"name":"Sekretariat HME","building_function":"Ormawa","department":"Elektro","num_of_classes":"1","num_of_floors":"1","hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/sekre_hme.jpg","description":"Merupakan sebuah sekretariat untuk Himpunan Mahasiswa jurusan Teknik Elektro yang digunakan oleh HMJ-E untuk melakukan kegiatan yang berhubungan dengan jurusan Teknik Elektro seperti rapat, pertemuan antar HMJ\/UKM dan lainnya.","website":"https:\/\/hmepoliban.com\/","location_coords":[[[-3.2958467,114.5824045],[-3.2958784,114.5824393],[-3.2958447,114.5824701],[-3.295813,114.5824353],[-3.2958467,114.5824045]]]},{"code":null,"name":"Sekretariat HMM","building_function":"Ormawa","department":"Mesin","num_of_classes":"1","num_of_floors":"1","hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/sekre_hmm.jpg","description":"Merupakan sebuah sekretariat untuk Himpunan Mahasiswa jurusan Teknik Mesin yang digunakan oleh HMJ-M untuk melakukan kegiatan yang berhubungan dengan jurusan Teknik Mesin seperti rapat, pertemuan antar HMJ\/UKM dan lainnya.","website":"https:\/\/taplink.cc\/hmmpoliban","location_coords":[[[-3.2957639,114.582959],[-3.2957933,114.5829946],[-3.2958429,114.5829485],[-3.295813,114.5829143],[-3.2957639,114.582959]]]},{"code":null,"name":"Sekretariat HMB","building_function":"Ormawa","department":"Bisnis","num_of_classes":"1","num_of_floors":"1","hours":"Senin-Jumat        07.00-18.00 Sabtu-Minggu      07.00-18.00","photo":"..\/images\/sekre_hmb.jpg","description":"Merupakan sebuah sekretariat untuk Himpunan Mahasiswa jurusan Teknik Mesin yang digunakan oleh HMJ-M untuk melakukan kegiatan yang berhubungan dengan jurusan Teknik Mesin seperti rapat, pertemuan antar HMB\/UKM dan lainnya.","website":"https:\/\/linktr.ee\/hmb.poliban","location_coords":[[[-3.2941452,114.5824848],[-3.294187,114.5825499],[-3.2942568,114.5825048],[-3.294215,114.5824398],[-3.2941452,114.5824848]]]},{"code":null,"name":"Gudang 1","building_function":null,"department":"Elektro","num_of_classes":"2","num_of_floors":"1","hours":"Tutup","photo":"..\/images\/gudang1.jpg","description":"Merupakan tempat untuk menyimpan peralatan-peralatan praktikum.","website":"https:\/\/poliban.ac.id\/elektro\/","location_coords":[[[-3.295613,114.581762],[-3.295575,114.581799],[-3.295618,114.581853],[-3.295662,114.581821],[-3.295613,114.581762]]]}];
            const map = initializeMap();
            const polygons = initializeLocations(map, locations);
            createFilterControl(map, locations, polygons);
            // Tambahkan event listener untuk tombol cari
            document.getElementById('searchInput').addEventListener('input', () => {
                searchLocations(map, locations, polygons);
            });
        }

        main();
    </script>
</body>

</html>