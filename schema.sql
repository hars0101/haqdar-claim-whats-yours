-- HaqDar – Claim What's Yours: database schema
-- Target: MySQL 8 / MariaDB, InnoDB, utf8mb4.
-- Tables are created in dependency order (schemes first).
-- Re-import is handled in README; this file contains no DROP statements.

SET NAMES utf8mb4;

-- Government scholarship schemes (master list).
CREATE TABLE IF NOT EXISTS schemes (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  slug            VARCHAR(80)  NOT NULL UNIQUE,
  name            VARCHAR(255) NOT NULL,
  provider        VARCHAR(255) NULL,
  level           ENUM('central','telangana') NOT NULL,
  benefit_text    VARCHAR(500) NULL,
  official_url    VARCHAR(500) NULL,
  last_verified   DATE NULL,
  is_active       TINYINT(1) NOT NULL DEFAULT 1,
  admin_notes     TEXT NULL,
  created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at      DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Eligibility rules. All rules of a scheme must pass for a "likely eligible" result.
CREATE TABLE IF NOT EXISTS scheme_rules (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  scheme_id   INT NOT NULL,
  field       VARCHAR(32)  NOT NULL,   -- e.g. category, family_income, course
  operator    ENUM('<=','>=','<','>','=','!=','in','contains_any') NOT NULL,
  value       VARCHAR(255) NOT NULL,
  label       VARCHAR(255) NOT NULL,   -- human-readable text shown to the student
  sort_order  INT NOT NULL DEFAULT 0,
  FOREIGN KEY (scheme_id) REFERENCES schemes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Documents to gather for a scheme. cond_field/cond_value make a document conditional
-- (e.g. disability certificate only when disability = true).
CREATE TABLE IF NOT EXISTS scheme_documents (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  scheme_id      INT NOT NULL,
  document_name  VARCHAR(255) NOT NULL,
  notes          VARCHAR(500) NULL,
  cond_field     VARCHAR(32)  NULL,
  cond_value     VARCHAR(255) NULL,
  sort_order     INT NOT NULL DEFAULT 0,
  FOREIGN KEY (scheme_id) REFERENCES schemes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Plain-language application steps, in order.
CREATE TABLE IF NOT EXISTS scheme_steps (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  scheme_id  INT NOT NULL,
  step_no    INT NOT NULL,
  body       TEXT NOT NULL,
  FOREIGN KEY (scheme_id) REFERENCES schemes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Key dates per scheme.
CREATE TABLE IF NOT EXISTS scheme_deadlines (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  scheme_id      INT NOT NULL,
  deadline_date  DATE NOT NULL,
  label          VARCHAR(255) NOT NULL,
  FOREIGN KEY (scheme_id) REFERENCES schemes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Anonymous browser sessions. Only an optional email is stored; no other personal data.
CREATE TABLE IF NOT EXISTS sessions (
  sid         VARCHAR(64) PRIMARY KEY,
  email       VARCHAR(255) NULL,
  created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Per-session tracking of application progress for a scheme.
CREATE TABLE IF NOT EXISTS applications (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  sid         VARCHAR(64) NOT NULL,
  scheme_id   INT NOT NULL,
  status      ENUM('not_started','documents_ready','applied','under_verification','received')
              NOT NULL DEFAULT 'not_started',
  created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_sid_scheme (sid, scheme_id),
  INDEX idx_sid (sid),
  FOREIGN KEY (scheme_id) REFERENCES schemes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Deadline reminders already sent, so the same reminder is never sent twice.
CREATE TABLE IF NOT EXISTS reminder_log (
  sid            VARCHAR(64) NOT NULL,
  scheme_id      INT NOT NULL,
  deadline_date  DATE NOT NULL,
  days_before    TINYINT NOT NULL,
  sent_at        DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (sid, scheme_id, deadline_date, days_before)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
