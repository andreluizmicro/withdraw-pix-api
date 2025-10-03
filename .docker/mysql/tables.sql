-- Tabela de contas
CREATE TABLE account (
                         id CHAR(36) NOT NULL PRIMARY KEY, -- UUID
                         name VARCHAR(255) NOT NULL,
                         balance DECIMAL(15,2) NOT NULL DEFAULT 0.00,
                         status ENUM('active', 'inactive') DEFAULT 'active',
                         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                         updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela de saques
CREATE TABLE account_withdraw (
                                  id CHAR(36) NOT NULL PRIMARY KEY, -- UUID
                                  account_id CHAR(36) NOT NULL,
                                  method VARCHAR(50) NOT NULL, -- ex: PIX, TED, DOC
                                  amount DECIMAL(15,2) NOT NULL,
                                  scheduled BOOLEAN DEFAULT FALSE,
                                  scheduled_for DATETIME NULL,
                                  done BOOLEAN DEFAULT FALSE,
                                  error BOOLEAN DEFAULT FALSE,
                                  error_reason VARCHAR(255) NULL,
                                  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                  FOREIGN KEY (account_id) REFERENCES account(id)
                                      ON DELETE CASCADE
                                      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabela de saques PIX
CREATE TABLE account_withdraw_pix (
                                      id CHAR(36) NOT NULL PRIMARY KEY, -- UUID para cada PIX
                                      account_withdraw_id CHAR(36) NOT NULL,
                                      type ENUM('cpf','cnpj','email','phone','random') NOT NULL,
                                      `key` VARCHAR(255) NOT NULL,
                                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                      FOREIGN KEY (account_withdraw_id) REFERENCES account_withdraw(id)
                                          ON DELETE CASCADE
                                          ON UPDATE CASCADE
) ENGINE=InnoDB;
