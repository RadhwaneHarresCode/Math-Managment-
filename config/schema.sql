CREATE DATABASE IF NOT EXISTS mathplan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mathplan;

CREATE TABLE IF NOT EXISTS users (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(191)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    role       ENUM('student','admin') DEFAULT 'student',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sessions (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    token      VARCHAR(64)  NOT NULL UNIQUE,
    expires_at DATETIME     NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS plan_days (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    day_number       TINYINT UNSIGNED NOT NULL UNIQUE,
    week             TINYINT UNSIGNED NOT NULL,
    phase            VARCHAR(20)      NOT NULL,
    title            VARCHAR(100)     NOT NULL,
    topics           JSON             NOT NULL,
    duration_minutes SMALLINT DEFAULT 120
);

CREATE TABLE IF NOT EXISTS progress (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id      INT UNSIGNED NOT NULL,
    day_id       INT UNSIGNED NOT NULL,
    completed    TINYINT(1)   DEFAULT 0,
    completed_at DATETIME     NULL,
    notes        TEXT         NULL,
    updated_at   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_day (user_id, day_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (day_id)  REFERENCES plan_days(id) ON DELETE CASCADE
);

INSERT IGNORE INTO plan_days (day_number,week,phase,title,topics) VALUES
(1,1,'algebra','Number Systems','["Integers & real numbers","Order of operations (PEMDAS)"]'),
(2,1,'algebra','Fractions & Decimals','["Simplification, addition/subtraction","Multiplication & division of fractions"]'),
(3,1,'algebra','Ratios & Percentages','["Ratio problems","Percentage increase/decrease"]'),
(4,1,'algebra','Algebra Basics','["Variables & expressions","Simplifying algebraic expressions"]'),
(5,1,'algebra','Linear Equations','["Solving one-variable equations","Word problems to equations"]'),
(6,1,'algebra','Inequalities','["Solving inequalities","Graphing on number line"]'),
(7,1,'algebra','Week 1 Review','["Redo hardest exercises","Mixed problem set"]'),
(8,2,'geometry','Lines & Angles','["Types of angles","Parallel lines & transversals"]'),
(9,2,'geometry','Triangles','["Properties & types","Pythagorean theorem"]'),
(10,2,'geometry','Polygons','["Quadrilaterals & regular polygons","Interior/exterior angle sums"]'),
(11,2,'geometry','Circles','["Circumference & area","Arc length & sector area"]'),
(12,2,'geometry','Area & Perimeter','["Composite figures","Real-world area problems"]'),
(13,2,'geometry','Volume & Surface Area','["Prisms, cylinders, cones","Spheres & composite solids"]'),
(14,2,'geometry','Week 2 Review','["Geometry formula sheet review","Timed practice problems"]'),
(15,3,'functions','Coordinate Plane','["Plotting points & quadrants","Distance & midpoint formula"]'),
(16,3,'functions','Linear Functions','["Slope & y-intercept","Graphing y = mx + b"]'),
(17,3,'functions','Systems of Equations','["Substitution method","Elimination method"]'),
(18,3,'functions','Quadratic Equations','["Factoring trinomials","Quadratic formula"]'),
(19,3,'functions','Quadratic Functions','["Parabola vertex & axis","Graphing quadratics"]'),
(20,3,'functions','Exponents & Roots','["Laws of exponents","Simplifying radical expressions"]'),
(21,3,'functions','Week 3 Review','["Functions & graphs mixed test","Error correction session"]'),
(22,4,'review','Statistics Basics','["Mean, median, mode, range","Reading charts & graphs"]'),
(23,4,'review','Probability','["Basic probability rules","Combinations & permutations"]'),
(24,4,'review','Word Problems Sprint','["Algebra word problems","Geometry word problems"]'),
(25,4,'review','Mock Exam Part 1','["Days 1-15 material only","Timed: 2 hours, no notes"]'),
(26,4,'review','Mock Exam Part 2','["Days 16-24 material only","Timed: 2 hours, no notes"]'),
(27,4,'review','Error Analysis','["Review both mock exams","List & re-study weak areas"]'),
(28,4,'review','Speed Drills','["Mental math & quick calculations","Time management strategies"]'),
(29,4,'review','Final Full Mock Exam','["Complete exam simulation","Strict time conditions"]'),
(30,4,'review','Final Review & Rest','["Light review of formulas only","Rest, sleep well - you are ready!"]');

-- Default admin: password is Admin1234!
INSERT IGNORE INTO users (name,email,password,role) VALUES
('Admin','admin@mathplan.dev','$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin');
