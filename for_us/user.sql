USE find_the_five;

INSERT INTO users (name, username, email, password_hash, role) VALUES
  ('Admin One', 'admin1', 'admin1@ftf.local', '$2y$12$N6cPOdckUNafbUKa5IR5JOVO7yLenTznpuR8OU0mm5mFExKU2.bNG', 'admin'),
  ('User One', 'user1', 'user1@ftf.local', '$2y$12$Y74QmWaU3nnOT9jDN2d6sucM.hr20S28BHGIvQC8SSbTsnUPSfTK2', 'user'),
  ('User Two', 'user2', 'user2@ftf.local', '$2y$12$5CYprPXBYSvjBJdHhee01e8hB9MUWxq6T2Gh/13D.XzwZyPE1GGzC', 'user'),
  ('User Three', 'user3', 'user3@ftf.local', '$2y$12$t2sPUbXMa7blfy8Ow2gOy.0ggBb0TaLd8aBkVF8wCe7UFDpYMUbhy', 'user'),
  ('User Four', 'user4', 'user4@ftf.local', '$2y$12$nqEmvNDXI9Ar1Z7Hn08yvuTMV4eZAXd8njAcxgqk6AQTnnWt9Mt6.', 'user');
