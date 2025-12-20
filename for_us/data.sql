USE find_the_five;

INSERT INTO flags (lab_key, flag_value) VALUES
  ('sqli', 'FLAG{SQLI_BYPASS_MASTER}'),
  ('idor', 'FLAG{IDOR_UNLOCKED_PROFILE}'),
  ('xss', 'FLAG{STORED_XSS_OWNED}'),
  ('cookie', 'FLAG{COOKIE_TRUST_IS_BAD}'),
  ('privesc', 'FLAG{ROLE_TAMPERING_SUCCESS}'),
  ('final', 'FLAG{FIND_THE_FIVE_COMPLETE}'),
  ('final_code', 'FTF-MASTER-KEY-204')
ON DUPLICATE KEY UPDATE flag_value = VALUES(flag_value);
