-- TODO: human-verify every value (income limits, benefit amounts, deadlines, documents, URLs) before go-live.
-- Seed data for HaqDar – Claim What's Yours. Requires schema.sql to be imported first.
-- Scheme ids are fixed 1..12 so child rows can reference them directly.

SET NAMES utf8mb4;

-- ============================================================
-- Scheme 1: nsp-post-matric-sc
-- TODO: human-verify
-- ============================================================
INSERT INTO schemes (id, slug, name, provider, level, benefit_text, official_url, last_verified, is_active, admin_notes)
VALUES (1, 'nsp-post-matric-sc', 'NSP Post-Matric Scholarship for SC', 'Ministry of Social Justice & Empowerment', 'central', 'Yearly tuition and maintenance support for SC students in post-matric courses.', 'https://scholarships.gov.in', '2026-11-22', 1, NULL);
-- TODO: human-verify thresholds: category, family income limit (Rs 2,50,000), education levels
INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES
(1, 'category', '=', 'SC', 'Category is SC', 1),
(1, 'family_income', '<=', '250000', 'Family income ≤ ₹2,50,000', 2),
(1, 'education_level', 'in', 'intermediate,diploma,ug,pg,phd', 'Studying intermediate, diploma or degree', 3);
INSERT INTO scheme_documents (scheme_id, document_name, notes, cond_field, cond_value, sort_order) VALUES
(1, 'Aadhaar card (copy)', 'Copy only. Name is enough on the form.', NULL, NULL, 1),
(1, 'Caste certificate', 'SC certificate from MRO / tahsildar.', NULL, NULL, 2),
(1, 'Income certificate', 'From MRO / tahsildar, current year.', NULL, NULL, 3),
(1, 'Bonafide / college certificate', 'Issued by your college or school.', NULL, NULL, 4),
(1, 'Previous year marksheet', 'Copy of last exam marksheet.', NULL, NULL, 5),
(1, 'Bank passbook first page', 'Account must be in your own name.', NULL, NULL, 6),
(1, 'Passport-size photo', 'Recent colour photo.', NULL, NULL, 7),
(1, 'Fee receipt', 'Receipt for this year fees.', NULL, NULL, 8);
INSERT INTO scheme_steps (scheme_id, step_no, body) VALUES
(1, 1, 'Open scholarships.gov.in on your phone or computer.'),
(1, 2, 'Click New Registration and choose your state.'),
(1, 3, 'Enter your mobile number and verify the OTP.'),
(1, 4, 'Fill in your personal, caste and income details exactly as on your certificates.'),
(1, 5, 'Upload your documents, then click Submit. Keep the application number safe.');
INSERT INTO scheme_deadlines (scheme_id, deadline_date, label) VALUES
(1, '2027-10-31', 'Application window closes (2026-27)');

-- ============================================================
-- Scheme 2: nsp-post-matric-st
-- TODO: human-verify
-- ============================================================
INSERT INTO schemes (id, slug, name, provider, level, benefit_text, official_url, last_verified, is_active, admin_notes)
VALUES (2, 'nsp-post-matric-st', 'NSP Post-Matric Scholarship for ST', 'Ministry of Tribal Affairs', 'central', 'Yearly tuition and maintenance support for ST students in post-matric courses.', 'https://scholarships.gov.in', '2026-11-22', 1, NULL);
-- TODO: human-verify thresholds: category, family income limit (Rs 2,50,000), education levels
INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES
(2, 'category', '=', 'ST', 'Category is ST', 1),
(2, 'family_income', '<=', '250000', 'Family income ≤ ₹2,50,000', 2),
(2, 'education_level', 'in', 'intermediate,diploma,ug,pg,phd', 'Studying intermediate, diploma or degree', 3);
INSERT INTO scheme_documents (scheme_id, document_name, notes, cond_field, cond_value, sort_order) VALUES
(2, 'Aadhaar card (copy)', 'Copy only. Name is enough on the form.', NULL, NULL, 1),
(2, 'Caste certificate', 'ST certificate from MRO / tahsildar.', NULL, NULL, 2),
(2, 'Income certificate', 'From MRO / tahsildar, current year.', NULL, NULL, 3),
(2, 'Bonafide / college certificate', 'Issued by your college or school.', NULL, NULL, 4),
(2, 'Previous year marksheet', 'Copy of last exam marksheet.', NULL, NULL, 5),
(2, 'Bank passbook first page', 'Account must be in your own name.', NULL, NULL, 6),
(2, 'Passport-size photo', 'Recent colour photo.', NULL, NULL, 7),
(2, 'Fee receipt', 'Receipt for this year fees.', NULL, NULL, 8);
INSERT INTO scheme_steps (scheme_id, step_no, body) VALUES
(2, 1, 'Open scholarships.gov.in on your phone or computer.'),
(2, 2, 'Click New Registration and choose your state.'),
(2, 3, 'Enter your mobile number and verify the OTP.'),
(2, 4, 'Fill in your personal, caste and income details exactly as on your certificates.'),
(2, 5, 'Upload your documents, then click Submit. Keep the application number safe.');
INSERT INTO scheme_deadlines (scheme_id, deadline_date, label) VALUES
(2, '2027-10-31', 'Application window closes (2026-27)');

-- ============================================================
-- Scheme 3: nsp-post-matric-obc
-- TODO: human-verify
-- ============================================================
INSERT INTO schemes (id, slug, name, provider, level, benefit_text, official_url, last_verified, is_active, admin_notes)
VALUES (3, 'nsp-post-matric-obc', 'NSP Post-Matric Scholarship for OBC/BC', 'Ministry of Social Justice & Empowerment', 'central', 'Yearly tuition and maintenance support for OBC/BC students in post-matric courses.', 'https://scholarships.gov.in', '2026-11-22', 1, NULL);
-- TODO: human-verify thresholds: category, family income limit (Rs 2,50,000), education levels
INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES
(3, 'category', '=', 'BC', 'Category is BC (OBC)', 1),
(3, 'family_income', '<=', '250000', 'Family income ≤ ₹2,50,000', 2),
(3, 'education_level', 'in', 'intermediate,diploma,ug,pg,phd', 'Studying intermediate, diploma or degree', 3);
INSERT INTO scheme_documents (scheme_id, document_name, notes, cond_field, cond_value, sort_order) VALUES
(3, 'Aadhaar card (copy)', 'Copy only. Name is enough on the form.', NULL, NULL, 1),
(3, 'Caste certificate', 'BC certificate from MRO / tahsildar.', NULL, NULL, 2),
(3, 'Income certificate', 'From MRO / tahsildar, current year.', NULL, NULL, 3),
(3, 'Bonafide / college certificate', 'Issued by your college or school.', NULL, NULL, 4),
(3, 'Previous year marksheet', 'Copy of last exam marksheet.', NULL, NULL, 5),
(3, 'Bank passbook first page', 'Account must be in your own name.', NULL, NULL, 6),
(3, 'Passport-size photo', 'Recent colour photo.', NULL, NULL, 7),
(3, 'Fee receipt', 'Receipt for this year fees.', NULL, NULL, 8);
INSERT INTO scheme_steps (scheme_id, step_no, body) VALUES
(3, 1, 'Open scholarships.gov.in on your phone or computer.'),
(3, 2, 'Click New Registration and choose your state.'),
(3, 3, 'Enter your mobile number and verify the OTP.'),
(3, 4, 'Fill in your personal, caste and income details exactly as on your certificates.'),
(3, 5, 'Upload your documents, then click Submit. Keep the application number safe.');
INSERT INTO scheme_deadlines (scheme_id, deadline_date, label) VALUES
(3, '2027-10-31', 'Application window closes (2026-27)');

-- ============================================================
-- Scheme 4: nsp-post-matric-minority
-- TODO: human-verify
-- ============================================================
INSERT INTO schemes (id, slug, name, provider, level, benefit_text, official_url, last_verified, is_active, admin_notes)
VALUES (4, 'nsp-post-matric-minority', 'NSP Post-Matric Scholarship for Minorities', 'Ministry of Minority Affairs', 'central', 'Yearly tuition and maintenance support for minority community students in post-matric courses.', 'https://scholarships.gov.in', '2026-11-22', 1, NULL);
-- TODO: human-verify thresholds: category, family income limit (Rs 2,50,000), education levels
INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES
(4, 'category', '=', 'Minority', 'Belongs to a minority community', 1),
(4, 'family_income', '<=', '250000', 'Family income ≤ ₹2,50,000', 2),
(4, 'education_level', 'in', 'intermediate,diploma,ug,pg,phd', 'Studying intermediate, diploma or degree', 3);
INSERT INTO scheme_documents (scheme_id, document_name, notes, cond_field, cond_value, sort_order) VALUES
(4, 'Aadhaar card (copy)', 'Copy only. Name is enough on the form.', NULL, NULL, 1),
(4, 'Caste / community certificate', 'Minority community certificate from MRO / tahsildar.', NULL, NULL, 2),
(4, 'Income certificate', 'From MRO / tahsildar, current year.', NULL, NULL, 3),
(4, 'Bonafide / college certificate', 'Issued by your college or school.', NULL, NULL, 4),
(4, 'Previous year marksheet', 'Copy of last exam marksheet.', NULL, NULL, 5),
(4, 'Bank passbook first page', 'Account must be in your own name.', NULL, NULL, 6),
(4, 'Passport-size photo', 'Recent colour photo.', NULL, NULL, 7),
(4, 'Fee receipt', 'Receipt for this year fees.', NULL, NULL, 8);
INSERT INTO scheme_steps (scheme_id, step_no, body) VALUES
(4, 1, 'Open scholarships.gov.in on your phone or computer.'),
(4, 2, 'Click New Registration and choose your state.'),
(4, 3, 'Enter your mobile number and verify the OTP.'),
(4, 4, 'Fill in your personal, caste and income details exactly as on your certificates.'),
(4, 5, 'Upload your documents, then click Submit. Keep the application number safe.');
INSERT INTO scheme_deadlines (scheme_id, deadline_date, label) VALUES
(4, '2027-10-31', 'Application window closes (2026-27)');

-- ============================================================
-- Scheme 5: central-sector-scholarship
-- TODO: human-verify
-- ============================================================
INSERT INTO schemes (id, slug, name, provider, level, benefit_text, official_url, last_verified, is_active, admin_notes)
VALUES (5, 'central-sector-scholarship', 'Central Sector Scheme of Scholarship (class 12 top marks to college)', 'Department of Higher Education', 'central', 'Yearly scholarship for students with high class 12 marks who join a degree course.', 'https://scholarships.gov.in', '2026-11-22', 1, NULL);
-- TODO: human-verify thresholds: marks percent (80), family income limit (Rs 8,00,000), education level
INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES
(5, 'marks_percent', '>=', '80', 'Class 12 marks 80% or above', 1),
(5, 'family_income', '<=', '800000', 'Family income ≤ ₹8,00,000', 2),
(5, 'education_level', '=', 'intermediate', 'Completed intermediate (class 12)', 3);
INSERT INTO scheme_documents (scheme_id, document_name, notes, cond_field, cond_value, sort_order) VALUES
(5, 'Aadhaar card (copy)', 'Copy only. Name is enough on the form.', NULL, NULL, 1),
(5, 'Class 12 marksheet', 'Original marksheet or copy.', NULL, NULL, 2),
(5, 'Income certificate', 'From MRO / tahsildar, current year.', NULL, NULL, 3),
(5, 'College admission letter', 'Letter from the degree college you joined.', NULL, NULL, 4),
(5, 'Bank passbook first page', 'Account must be in your own name.', NULL, NULL, 5),
(5, 'Passport-size photo', 'Recent colour photo.', NULL, NULL, 6);
INSERT INTO scheme_steps (scheme_id, step_no, body) VALUES
(5, 1, 'Open scholarships.gov.in on your phone or computer.'),
(5, 2, 'Click New Registration and choose the Central Sector scheme.'),
(5, 3, 'Enter your mobile number and verify the OTP.'),
(5, 4, 'Fill in your marks and family income exactly as on your certificates.'),
(5, 5, 'Upload your documents, then click Submit. Keep the application number safe.');
INSERT INTO scheme_deadlines (scheme_id, deadline_date, label) VALUES
(5, '2027-10-31', 'Application window closes (2026-27)');

-- ============================================================
-- Scheme 6: aicte-pragati
-- TODO: human-verify
-- ============================================================
INSERT INTO schemes (id, slug, name, provider, level, benefit_text, official_url, last_verified, is_active, admin_notes)
VALUES (6, 'aicte-pragati', 'AICTE Pragati Scholarship for Girls', 'AICTE', 'central', 'Yearly support for girl students in AICTE-approved technical diploma or degree courses.', 'https://www.aicte-india.org', '2026-11-22', 1, NULL);
-- TODO: human-verify thresholds: gender, family income limit (Rs 8,00,000), education levels, course keywords
INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES
(6, 'gender', '=', 'female', 'Girl student', 1),
(6, 'family_income', '<=', '800000', 'Family income ≤ ₹8,00,000', 2),
(6, 'education_level', 'in', 'diploma,ug', 'Studying diploma or degree', 3),
(6, 'course', 'contains_any', 'engineering,diploma,b.e,b.tech,technology,tech', 'Technical course (engineering / diploma)', 4);
INSERT INTO scheme_documents (scheme_id, document_name, notes, cond_field, cond_value, sort_order) VALUES
(6, 'Aadhaar card (copy)', 'Copy only. Name is enough on the form.', NULL, NULL, 1),
(6, 'Income certificate', 'From MRO / tahsildar, current year.', NULL, NULL, 2),
(6, 'Previous year marksheet', 'Class 10 and class 12 or last year marksheet.', NULL, NULL, 3),
(6, 'Bonafide / college certificate', 'Issued by your AICTE-approved college.', NULL, NULL, 4),
(6, 'Bank passbook first page', 'Account must be in your own name.', NULL, NULL, 5),
(6, 'Passport-size photo', 'Recent colour photo.', NULL, NULL, 6),
(6, 'Fee receipt', 'Receipt for this year fees.', NULL, NULL, 7);
INSERT INTO scheme_steps (scheme_id, step_no, body) VALUES
(6, 1, 'Open the National Scholarship Portal (scholarships.gov.in) on your phone or computer.'),
(6, 2, 'Click New Registration and choose the AICTE Pragati scheme.'),
(6, 3, 'Enter your mobile number and verify the OTP.'),
(6, 4, 'Fill in your college and family income details exactly as on your certificates.'),
(6, 5, 'Upload your documents, then click Submit. Keep the application number safe.');
INSERT INTO scheme_deadlines (scheme_id, deadline_date, label) VALUES
(6, '2027-10-31', 'Application window closes (2026-27)');

-- ============================================================
-- Scheme 7: aicte-saksham
-- TODO: human-verify
-- ============================================================
INSERT INTO schemes (id, slug, name, provider, level, benefit_text, official_url, last_verified, is_active, admin_notes)
VALUES (7, 'aicte-saksham', 'AICTE Saksham Scholarship for Differently Abled', 'AICTE', 'central', 'Yearly support for differently abled students in AICTE-approved technical diploma or degree courses.', 'https://www.aicte-india.org', '2026-11-22', 1, NULL);
-- TODO: human-verify thresholds: disability (40%), family income limit (Rs 8,00,000), course keywords
INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES
(7, 'disability', '=', 'true', 'Person with disability (≥40%)', 1),
(7, 'family_income', '<=', '800000', 'Family income ≤ ₹8,00,000', 2),
(7, 'course', 'contains_any', 'engineering,diploma,b.e,b.tech,technology,tech', 'Technical course (engineering / diploma)', 3);
INSERT INTO scheme_documents (scheme_id, document_name, notes, cond_field, cond_value, sort_order) VALUES
(7, 'Aadhaar card (copy)', 'Copy only. Name is enough on the form.', NULL, NULL, 1),
(7, 'Disability certificate (UDID or 40% or more)', 'UDID card or disability certificate from the authority.', 'disability', 'true', 2),
(7, 'Income certificate', 'From MRO / tahsildar, current year.', NULL, NULL, 3),
(7, 'Bonafide / college certificate', 'Issued by your AICTE-approved college.', NULL, NULL, 4),
(7, 'Previous year marksheet', 'Copy of last exam marksheet.', NULL, NULL, 5),
(7, 'Bank passbook first page', 'Account must be in your own name.', NULL, NULL, 6),
(7, 'Passport-size photo', 'Recent colour photo.', NULL, NULL, 7),
(7, 'Fee receipt', 'Receipt for this year fees.', NULL, NULL, 8);
INSERT INTO scheme_steps (scheme_id, step_no, body) VALUES
(7, 1, 'Open the National Scholarship Portal (scholarships.gov.in) on your phone or computer.'),
(7, 2, 'Click New Registration and choose the AICTE Saksham scheme.'),
(7, 3, 'Enter your mobile number and verify the OTP.'),
(7, 4, 'Enter your disability details exactly as on your UDID or disability certificate.'),
(7, 5, 'Upload your documents, then click Submit. Keep the application number safe.');
INSERT INTO scheme_deadlines (scheme_id, deadline_date, label) VALUES
(7, '2027-10-31', 'Application window closes (2026-27)');

-- ============================================================
-- Scheme 8: nmms
-- TODO: human-verify
-- ============================================================
INSERT INTO schemes (id, slug, name, provider, level, benefit_text, official_url, last_verified, is_active, admin_notes)
VALUES (8, 'nmms', 'National Means-cum-Merit Scholarship (Class 9)', 'Ministry of Education', 'central', 'Yearly scholarship for class 8 students from low-income families who join class 9 in a government or aided school.', 'https://scholarships.gov.in', '2026-11-22', 1, NULL);
-- TODO: human-verify thresholds: education level, family income limit (Rs 3,50,000), institution types
INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES
(8, 'education_level', '=', 'class_8', 'Studying class 8', 1),
(8, 'family_income', '<=', '350000', 'Family income ≤ ₹3,50,000', 2),
(8, 'institution_type', 'in', 'government,aided', 'Government or aided school', 3);
INSERT INTO scheme_documents (scheme_id, document_name, notes, cond_field, cond_value, sort_order) VALUES
(8, 'Aadhaar card (copy)', 'Copy only. Name is enough on the form.', NULL, NULL, 1),
(8, 'Income certificate', 'From MRO / tahsildar, current year.', NULL, NULL, 2),
(8, 'School transfer certificate', 'Only if asked by the school. Get it from your head master.', NULL, NULL, 3),
(8, 'Bonafide / school certificate', 'Issued by your school.', NULL, NULL, 4),
(8, 'Previous year marksheet', 'Class 8 marksheet copy.', NULL, NULL, 5),
(8, 'Bank passbook first page', 'Account must be in your own name.', NULL, NULL, 6),
(8, 'Passport-size photo', 'Recent colour photo.', NULL, NULL, 7);
INSERT INTO scheme_steps (scheme_id, step_no, body) VALUES
(8, 1, 'Ask your school head master about the NMMS exam and registration.'),
(8, 2, 'Open scholarships.gov.in on your phone or computer.'),
(8, 3, 'Click New Registration and enter your mobile number. Verify the OTP.'),
(8, 4, 'Fill in your school and family income details exactly as on your certificates.'),
(8, 5, 'Upload your documents, then click Submit. Keep the application number safe.');
INSERT INTO scheme_deadlines (scheme_id, deadline_date, label) VALUES
(8, '2027-01-31', 'Registration closes (class 9 entry 2027)');

-- ============================================================
-- Scheme 9: telangana-epass-post-matric
-- TODO: human-verify
-- ============================================================
INSERT INTO schemes (id, slug, name, provider, level, benefit_text, official_url, last_verified, is_active, admin_notes)
VALUES (9, 'telangana-epass-post-matric', 'Telangana ePASS Post-Matric Scholarship', 'Telangana welfare departments', 'telangana', 'Yearly scholarship and fee support for post-matric students of eligible communities in Telangana.', 'https://telanganaepass.cgg.gov.in', '2026-11-22', 1, NULL);
-- TODO: human-verify thresholds: state, categories, family income limit (Rs 2,50,000)
INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES
(9, 'state', '=', 'Telangana', 'State is Telangana', 1),
(9, 'category', 'in', 'SC,ST,BC,EWS,Minority', 'Category is SC, ST, BC, EWS or Minority', 2),
(9, 'family_income', '<=', '250000', 'Family income ≤ ₹2,50,000', 3);
INSERT INTO scheme_documents (scheme_id, document_name, notes, cond_field, cond_value, sort_order) VALUES
(9, 'Aadhaar card (copy)', 'Copy only. Name is enough on the form.', NULL, NULL, 1),
(9, 'Caste certificate', 'Community certificate from MRO / tahsildar.', NULL, NULL, 2),
(9, 'Income certificate', 'From MRO / tahsildar, current year.', NULL, NULL, 3),
(9, 'Bonafide / college certificate', 'Issued by your college.', NULL, NULL, 4),
(9, 'Previous year marksheet', 'Copy of last exam marksheet.', NULL, NULL, 5),
(9, 'Bank passbook first page', 'Account must be in your own name.', NULL, NULL, 6),
(9, 'Fee receipt', 'Receipt for this year fees.', NULL, NULL, 7);
INSERT INTO scheme_steps (scheme_id, step_no, body) VALUES
(9, 1, 'Open telanganaepass.cgg.gov.in on your phone or computer.'),
(9, 2, 'Click New Registration and enter your Aadhaar-linked mobile number.'),
(9, 3, 'Verify the OTP sent to your phone.'),
(9, 4, 'Fill in your college, caste and income details exactly as on your certificates.'),
(9, 5, 'Upload your documents, then click Submit. Keep the application number safe.');
INSERT INTO scheme_deadlines (scheme_id, deadline_date, label) VALUES
(9, '2027-03-31', 'Application window closes (2026-27)');

-- ============================================================
-- Scheme 10: telangana-rtf
-- TODO: human-verify
-- ============================================================
INSERT INTO schemes (id, slug, name, provider, level, benefit_text, official_url, last_verified, is_active, admin_notes)
VALUES (10, 'telangana-rtf', 'Telangana Reimbursement of Tuition Fee (RTF)', 'Telangana welfare departments', 'telangana', 'Reimbursement of tuition fee paid to the college for eligible students in Telangana.', 'https://telanganaepass.cgg.gov.in', '2026-11-22', 1, NULL);
-- TODO: human-verify thresholds: state, categories, family income limit (Rs 2,50,000)
INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES
(10, 'state', '=', 'Telangana', 'State is Telangana', 1),
(10, 'category', 'in', 'SC,ST,BC,EWS', 'Category is SC, ST, BC or EWS', 2),
(10, 'family_income', '<=', '250000', 'Family income ≤ ₹2,50,000', 3);
INSERT INTO scheme_documents (scheme_id, document_name, notes, cond_field, cond_value, sort_order) VALUES
(10, 'Aadhaar card (copy)', 'Copy only. Name is enough on the form.', NULL, NULL, 1),
(10, 'Caste certificate', 'Community certificate from MRO / tahsildar.', NULL, NULL, 2),
(10, 'Income certificate', 'From MRO / tahsildar, current year.', NULL, NULL, 3),
(10, 'Bonafide / college certificate', 'Issued by your college.', NULL, NULL, 4),
(10, 'Fee receipt', 'Receipt for the tuition fee paid.', NULL, NULL, 5),
(10, 'Bank passbook first page', 'Account must be in your own name.', NULL, NULL, 6);
INSERT INTO scheme_steps (scheme_id, step_no, body) VALUES
(10, 1, 'Open telanganaepass.cgg.gov.in on your phone or computer.'),
(10, 2, 'Click New Registration and enter your Aadhaar-linked mobile number.'),
(10, 3, 'Verify the OTP sent to your phone.'),
(10, 4, 'Fill in your college, caste and fee details exactly as on your receipts.'),
(10, 5, 'Upload your documents, then click Submit. Keep the application number safe.');
INSERT INTO scheme_deadlines (scheme_id, deadline_date, label) VALUES
(10, '2027-03-31', 'Application window closes (2026-27)');

-- ============================================================
-- Scheme 11: telangana-mtf
-- TODO: human-verify
-- ============================================================
INSERT INTO schemes (id, slug, name, provider, level, benefit_text, official_url, last_verified, is_active, admin_notes)
VALUES (11, 'telangana-mtf', 'Telangana Minorities Tuition Fee Reimbursement (MTF)', 'Telangana Minorities Welfare', 'telangana', 'Reimbursement of tuition fee paid to the college for eligible minority students in Telangana.', 'https://telanganaepass.cgg.gov.in', '2026-11-22', 1, NULL);
-- TODO: human-verify thresholds: state, category, family income limit (Rs 2,50,000)
INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES
(11, 'state', '=', 'Telangana', 'State is Telangana', 1),
(11, 'category', '=', 'Minority', 'Belongs to a minority community', 2),
(11, 'family_income', '<=', '250000', 'Family income ≤ ₹2,50,000', 3);
INSERT INTO scheme_documents (scheme_id, document_name, notes, cond_field, cond_value, sort_order) VALUES
(11, 'Aadhaar card (copy)', 'Copy only. Name is enough on the form.', NULL, NULL, 1),
(11, 'Caste / community certificate', 'Minority community certificate from MRO / tahsildar.', NULL, NULL, 2),
(11, 'Income certificate', 'From MRO / tahsildar, current year.', NULL, NULL, 3),
(11, 'Bonafide / college certificate', 'Issued by your college.', NULL, NULL, 4),
(11, 'Fee receipt', 'Receipt for the tuition fee paid.', NULL, NULL, 5),
(11, 'Bank passbook first page', 'Account must be in your own name.', NULL, NULL, 6);
INSERT INTO scheme_steps (scheme_id, step_no, body) VALUES
(11, 1, 'Open telanganaepass.cgg.gov.in on your phone or computer.'),
(11, 2, 'Click New Registration and enter your Aadhaar-linked mobile number.'),
(11, 3, 'Verify the OTP sent to your phone.'),
(11, 4, 'Fill in your college, community and fee details exactly as on your certificates.'),
(11, 5, 'Upload your documents, then click Submit. Keep the application number safe.');
INSERT INTO scheme_deadlines (scheme_id, deadline_date, label) VALUES
(11, '2027-03-31', 'Application window closes (2026-27)');

-- ============================================================
-- Scheme 12: pm-yasasvi
-- TODO: human-verify
-- ============================================================
INSERT INTO schemes (id, slug, name, provider, level, benefit_text, official_url, last_verified, is_active, admin_notes)
VALUES (12, 'pm-yasasvi', 'PM YASASVI Scholarship for OBC/EBC/DNT', 'Ministry of Social Justice & Empowerment', 'central', 'Yearly scholarship for OBC, EBC and DNT students in class 9 and above.', 'https://scholarships.gov.in', '2026-11-22', 1, NULL);
-- TODO: human-verify thresholds: category, family income limit (Rs 2,50,000), education levels
INSERT INTO scheme_rules (scheme_id, field, operator, value, label, sort_order) VALUES
(12, 'category', '=', 'BC', 'Category is BC (OBC / EBC / DNT)', 1),
(12, 'family_income', '<=', '250000', 'Family income ≤ ₹2,50,000', 2),
(12, 'education_level', 'in', 'class_9_10,intermediate,diploma,ug,pg', 'Studying class 9 to post-graduation', 3);
INSERT INTO scheme_documents (scheme_id, document_name, notes, cond_field, cond_value, sort_order) VALUES
(12, 'Aadhaar card (copy)', 'Copy only. Name is enough on the form.', NULL, NULL, 1),
(12, 'Caste certificate', 'BC / EBC certificate from MRO / tahsildar.', NULL, NULL, 2),
(12, 'Income certificate', 'From MRO / tahsildar, current year.', NULL, NULL, 3),
(12, 'Bonafide / school or college certificate', 'Issued by your school or college.', NULL, NULL, 4),
(12, 'Previous year marksheet', 'Copy of last exam marksheet.', NULL, NULL, 5),
(12, 'Bank passbook first page', 'Account must be in your own name.', NULL, NULL, 6),
(12, 'Passport-size photo', 'Recent colour photo.', NULL, NULL, 7);
INSERT INTO scheme_steps (scheme_id, step_no, body) VALUES
(12, 1, 'Open the National Scholarship Portal (scholarships.gov.in) on your phone or computer.'),
(12, 2, 'Click New Registration and choose PM YASASVI.'),
(12, 3, 'Enter your mobile number and verify the OTP.'),
(12, 4, 'Fill in your school or college and income details exactly as on your certificates.'),
(12, 5, 'Upload your documents, then click Submit. Keep the application number safe.');
INSERT INTO scheme_deadlines (scheme_id, deadline_date, label) VALUES
(12, '2027-10-31', 'Application window closes (2026-27)');
