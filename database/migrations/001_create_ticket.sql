CREATE TABLE IF NOT EXISTS tickets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    project_id INTEGER NOT NULL,
    client_id INTEGER NOT NULL,
    priority TEXT CHECK(priority IN ('p1', 'p2', 'p3')) DEFAULT 'p2',
    status TEXT CHECK(status IN ('open', 'in_progress', 'closed')) DEFAULT 'open',
    evolution TEXT DEFAULT '',
    user_id INTEGER, -- rapporteur (creator)
    developer_id INTEGER, -- handle assignee
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);
