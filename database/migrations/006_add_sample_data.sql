-- USERS
INSERT INTO users (username, password, role) VALUES
('dev', '$2y$12$kuR5epXlCiEko3ZttESf/e5EzHZ/h4swINwR/n9ZvuBfNK35hOnQS', 'developpeur'),
('rap', '$2y$12$kuR5epXlCiEko3ZttESf/e5EzHZ/h4swINwR/n9ZvuBfNK35hOnQS', 'rapporteur');

--   dev / test1234
--   rap / test1234


-- CLIENTS
INSERT INTO clients (name, contact_email, contact_phone) VALUES
('Client A', 'a@example.com', '0101010101'),
('Client B', 'b@example.com', '0202020202');

-- PROJECTS
INSERT INTO projects (name, description, client_id) VALUES
('Project Alpha', 'Alpha project for Client A', 1),
('Project Beta', 'Beta project for Client B', 2);

-- TICKETS
INSERT INTO tickets (title, description, project_id, client_id, priority, status, user_id, developer_id, evolution)
VALUES
('Login failure', 'Users cannot login after update.', 1, 1, 'p1', 'open', 2, 1, 'Hotfix in progress'),
('UI glitch', 'Alignment issue on dashboard.', 2, 2, 'p3', 'in_progress', 2, 1, 'UI review pending'),
('Crash on Save', 'App crashes when saving user data.', 2, 2, 'p1', 'open', 2, NULL, 'Awaiting developer assignment'),
('Slow report generation', 'Reports take too long to load.', 1, 1, 'p2', 'open', 2, 1, 'Monitoring performance');
