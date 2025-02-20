// Impor semua CSS/SASS di folder admin secara otomatis
// function importAll(r) {
//     r.keys().forEach(r);
// }

// // Import semua CSS/SASS dalam folder `admin`
importAll(require.context("../../css/admin", true, /\.(css|scss|sass)$/));
import "./custom.js";
// import "../../css/tailwind.css";