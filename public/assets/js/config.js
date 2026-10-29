/* ToonApp Hub settings.
   The admin logs in on the normal Log in page with this email and their password.
   Only a scrambled (hashed) form of the password is stored here.
   To change the admin email or password: Admin > Settings > Admin login,
   then replace this file with the config.js that page gives you. */
window.TOON_CONFIG = {
  storeName: "ToonApp Hub",
  admin: {
    email: "admin@tooabihub.com",
    salt: "slhr7q29qfjkH3fR2uw8DA==",
    hash: "X/gzGt6uVe7Hg9kOw/YsYleXdjlJJl1YHbkYo7AMZtU="
  }
};
