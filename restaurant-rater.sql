--
-- PostgreSQL database dump
--

-- Dumped from database version 16.2 (Debian 16.2-1.pgdg120+2)
-- Dumped by pg_dump version 16.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: restaurant-rater; Type: DATABASE; Schema: -; Owner: docker
--

CREATE DATABASE "restaurant-rater" WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'en_US.utf8';


ALTER DATABASE "restaurant-rater" OWNER TO docker;

\connect -reuse-previous=on "dbname='restaurant-rater'"

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: log_restaurant_activity(); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.log_restaurant_activity() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    action_type TEXT;
BEGIN
    -- Determine the action type
    IF TG_OP = 'INSERT' THEN
        action_type := 'INSERT';
    ELSIF TG_OP = 'UPDATE' THEN
        action_type := 'UPDATE';
    ELSIF TG_OP = 'DELETE' THEN
        action_type := 'DELETE';
    END IF;

    -- Log the action
    INSERT INTO public.is_log (action_name, create_at, restaurant_id)
    VALUES (action_type, now(), COALESCE(NEW.restaurant_id, OLD.restaurant_id));

    -- Return the appropriate row
    RETURN COALESCE(NEW, OLD);
END;
$$;


ALTER FUNCTION public.log_restaurant_activity() OWNER TO docker;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: is_address; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.is_address (
    address_id integer NOT NULL,
    street character varying(255),
    city character varying(128),
    postal_code character varying(32),
    house_no character varying(32),
    apartment_no character varying(32)
);


ALTER TABLE public.is_address OWNER TO docker;

--
-- Name: is_address_address_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.is_address_address_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.is_address_address_id_seq OWNER TO docker;

--
-- Name: is_address_address_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.is_address_address_id_seq OWNED BY public.is_address.address_id;


--
-- Name: is_log; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.is_log (
    log_id integer NOT NULL,
    action_name character varying(128) NOT NULL,
    create_at timestamp without time zone NOT NULL,
    restaurant_id integer NOT NULL
);


ALTER TABLE public.is_log OWNER TO docker;

--
-- Name: is_log_log_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.is_log_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.is_log_log_id_seq OWNER TO docker;

--
-- Name: is_log_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.is_log_log_id_seq OWNED BY public.is_log.log_id;


--
-- Name: is_restaurant; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.is_restaurant (
    restaurant_id integer NOT NULL,
    address_id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    image character varying(64),
    email character varying(255),
    website character varying(255),
    publicate boolean DEFAULT true NOT NULL,
    status boolean DEFAULT true NOT NULL,
    phone character varying(16)
);


ALTER TABLE public.is_restaurant OWNER TO docker;

--
-- Name: is_restaurant_restaurant_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.is_restaurant_restaurant_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.is_restaurant_restaurant_id_seq OWNER TO docker;

--
-- Name: is_restaurant_restaurant_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.is_restaurant_restaurant_id_seq OWNED BY public.is_restaurant.restaurant_id;


--
-- Name: is_review; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.is_review (
    review_id integer NOT NULL,
    restaurant_id integer NOT NULL,
    user_id integer NOT NULL,
    rate integer NOT NULL,
    review text NOT NULL,
    publicate boolean DEFAULT true NOT NULL,
    status boolean DEFAULT true NOT NULL,
    create_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.is_review OWNER TO docker;

--
-- Name: is_review_review_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.is_review_review_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.is_review_review_id_seq OWNER TO docker;

--
-- Name: is_review_review_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.is_review_review_id_seq OWNED BY public.is_review.review_id;


--
-- Name: is_role; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.is_role (
    role_id integer NOT NULL,
    name character varying(64) NOT NULL
);


ALTER TABLE public.is_role OWNER TO docker;

--
-- Name: is_role_role_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.is_role_role_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.is_role_role_id_seq OWNER TO docker;

--
-- Name: is_role_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.is_role_role_id_seq OWNED BY public.is_role.role_id;


--
-- Name: is_user; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.is_user (
    user_id integer NOT NULL,
    name character varying(128) NOT NULL,
    password text NOT NULL,
    email character varying(255) NOT NULL,
    publicate boolean DEFAULT true NOT NULL,
    status boolean DEFAULT true NOT NULL,
    create_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    role_id integer NOT NULL
);


ALTER TABLE public.is_user OWNER TO docker;

--
-- Name: is_user_user_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.is_user_user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.is_user_user_id_seq OWNER TO docker;

--
-- Name: is_user_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.is_user_user_id_seq OWNED BY public.is_user.user_id;


--
-- Name: vw_restaurant_details; Type: VIEW; Schema: public; Owner: docker
--

CREATE VIEW public.vw_restaurant_details AS
SELECT
    NULL::integer AS restaurant_id,
    NULL::character varying(255) AS name,
    NULL::text AS description,
    NULL::character varying(64) AS image,
    NULL::character varying(255) AS email,
    NULL::character varying(16) AS phone,
    NULL::character varying(255) AS website,
    NULL::integer AS address_id,
    NULL::character varying(255) AS street,
    NULL::character varying(128) AS city,
    NULL::character varying(32) AS postal_code,
    NULL::character varying(32) AS house_no,
    NULL::character varying(32) AS apartment_no,
    NULL::numeric AS rate,
    NULL::boolean AS publicate,
    NULL::boolean AS status;


ALTER VIEW public.vw_restaurant_details OWNER TO docker;

--
-- Name: is_address address_id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_address ALTER COLUMN address_id SET DEFAULT nextval('public.is_address_address_id_seq'::regclass);


--
-- Name: is_log log_id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_log ALTER COLUMN log_id SET DEFAULT nextval('public.is_log_log_id_seq'::regclass);


--
-- Name: is_restaurant restaurant_id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_restaurant ALTER COLUMN restaurant_id SET DEFAULT nextval('public.is_restaurant_restaurant_id_seq'::regclass);


--
-- Name: is_review review_id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_review ALTER COLUMN review_id SET DEFAULT nextval('public.is_review_review_id_seq'::regclass);


--
-- Name: is_role role_id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_role ALTER COLUMN role_id SET DEFAULT nextval('public.is_role_role_id_seq'::regclass);


--
-- Name: is_user user_id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_user ALTER COLUMN user_id SET DEFAULT nextval('public.is_user_user_id_seq'::regclass);


--
-- Data for Name: is_address; Type: TABLE DATA; Schema: public; Owner: docker
--

INSERT INTO public.is_address (address_id, street, city, postal_code, house_no, apartment_no) VALUES (2, 'Stanisława Staszica', 'Jasło', '38-200', '5', '');
INSERT INTO public.is_address (address_id, street, city, postal_code, house_no, apartment_no) VALUES (1, 'Basztowa', 'Kraków', '31-143', '17', '');
INSERT INTO public.is_address (address_id, street, city, postal_code, house_no, apartment_no) VALUES (5, 'Grunwaldzka', 'Rzeszów', '35-064', '24', '');
INSERT INTO public.is_address (address_id, street, city, postal_code, house_no, apartment_no) VALUES (6, '<script>alert("test3")</script>', '<script>alert("test4")</script>', '30-060', '<script>alert("test5")</script>', '<script>alert("test6")</script>');
INSERT INTO public.is_address (address_id, street, city, postal_code, house_no, apartment_no) VALUES (4, 'plac Wolnica', 'Kraków', '30-060', '13', '');
INSERT INTO public.is_address (address_id, street, city, postal_code, house_no, apartment_no) VALUES (7, 'Dereniowa', 'Warszawa', '02-776', '10D', '');
INSERT INTO public.is_address (address_id, street, city, postal_code, house_no, apartment_no) VALUES (8, 'test', 'test', '30-060', 'test', '');


--
-- Data for Name: is_log; Type: TABLE DATA; Schema: public; Owner: docker
--

INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (1, 'UPDATE', '2024-05-02 19:48:08.965893', 4);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (2, 'UPDATE', '2024-05-02 19:48:14.993144', 4);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (3, 'UPDATE', '2024-05-03 12:22:10.299716', 3);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (4, 'UPDATE', '2024-05-03 12:22:10.783428', 2);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (5, 'UPDATE', '2024-05-03 12:22:11.559615', 4);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (6, 'UPDATE', '2024-05-03 12:22:15.02021', 2);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (7, 'UPDATE', '2024-05-03 12:22:15.366206', 3);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (8, 'UPDATE', '2024-05-03 12:22:15.872251', 4);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (9, 'UPDATE', '2024-05-03 12:24:46.703761', 3);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (10, 'UPDATE', '2024-05-03 12:24:47.718392', 3);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (11, 'UPDATE', '2024-05-03 12:24:49.833463', 4);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (12, 'UPDATE', '2024-05-03 12:24:51.451957', 4);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (13, 'INSERT', '2024-05-03 12:36:12.243159', 6);
INSERT INTO public.is_log (log_id, action_name, create_at, restaurant_id) VALUES (14, 'INSERT', '2024-05-03 12:42:20.333958', 7);


--
-- Data for Name: is_restaurant; Type: TABLE DATA; Schema: public; Owner: docker
--

INSERT INTO public.is_restaurant (restaurant_id, address_id, name, description, image, email, website, publicate, status, phone) VALUES (1, 1, 'Efes kebab', 'Oferujemy potrawy kuchni tureckiej oraz polskiej w niepowtarzalnym klimacie.

Nasza specjalność to kebab na cienkim cieście, frytki z posypką , sałatki, kanapki panini, burgery. Dzięki produktom wysokiej jakości wyróżniamy się smakiem, jakością serwowanych dań oraz estetyką podania.

Codziennie nasza załoga przygotowuje dla Państwa świeże surówki, sałatki, sosy - wszystko z naturalnych składników. Dzięki jakości i dbałości o każdy szczegół cieszymy się licznym zaufaniem Naszych Gości.<script>alert(''HACKED'');</script>', '512x512bb.jpg', 'efes.krakow@gmail.com', 'https://kebabkrakow24.pl/', true, true, '123123123');
INSERT INTO public.is_restaurant (restaurant_id, address_id, name, description, image, email, website, publicate, status, phone) VALUES (2, 2, 'Doner king', 'Witaj w Doner King – miejscu, gdzie miłość do kulinariów spotyka się z doskonałością!
Odwiedź nas w Jaśle, Gorlicach lub Nowym Sączu i doświadcz wyjątkowej atmosfery.
Zapraszamy do miejsca – gdzie każdy kęs jest historią!', 'cropped-DONER-KING-ZIELONE-LOGO-500x500-1.png', '', 'https://donerking.pl/', true, true, '795520665');
INSERT INTO public.is_restaurant (restaurant_id, address_id, name, description, image, email, website, publicate, status, phone) VALUES (3, 4, 'Kebab pod 13', 'Położona pośród zwartej zabudowy prosta knajpka z kebabem oraz ofertą mięs i dań wegetariańskich.', 'logotyp.jpg', '', 'https://kebabpod13.pl/', true, true, '');
INSERT INTO public.is_restaurant (restaurant_id, address_id, name, description, image, email, website, publicate, status, phone) VALUES (4, 5, 'Dara kebab', 'Opis testowy', 'logo_465x320.png', '', '', true, true, '');
INSERT INTO public.is_restaurant (restaurant_id, address_id, name, description, image, email, website, publicate, status, phone) VALUES (6, 7, 'Kebab Fenicja', 'Kebaby to nasza specjalność! Przekonaj się!
Zawsze pysznie, zawsze dobrze, zawsze szybko!
إلى اللِقَاءِ', 'images.jpg', 'restauracja@fenicja.com', 'https://www.fenicja.com/', true, true, '501694840');
INSERT INTO public.is_restaurant (restaurant_id, address_id, name, description, image, email, website, publicate, status, phone) VALUES (7, 8, 'test', '', NULL, '', '', true, true, '');
INSERT INTO public.is_restaurant (restaurant_id, address_id, name, description, image, email, website, publicate, status, phone) VALUES (5, 6, '<script>alert("test1")</script>', '<script>alert("test2")</script>                   asdfadsf                            adfafdsafdas               asdfdsa', NULL, '', '', false, true, '');


--
-- Data for Name: is_review; Type: TABLE DATA; Schema: public; Owner: docker
--

INSERT INTO public.is_review (review_id, restaurant_id, user_id, rate, review, publicate, status, create_at) VALUES (2, 1, 1, 4, 'to jest moja opinia :D', true, true, '2024-04-23 19:12:33.891717');
INSERT INTO public.is_review (review_id, restaurant_id, user_id, rate, review, publicate, status, create_at) VALUES (3, 1, 2, 3, 'test', true, true, '2024-04-23 21:59:38.776134');
INSERT INTO public.is_review (review_id, restaurant_id, user_id, rate, review, publicate, status, create_at) VALUES (4, 2, 1, 5, 'Najlepszy kebab', true, true, '2024-04-24 20:04:18.098067');
INSERT INTO public.is_review (review_id, restaurant_id, user_id, rate, review, publicate, status, create_at) VALUES (5, 3, 1, 4, 'Dobre mięso ale jest drogo', true, true, '2024-04-30 13:53:30.127506');
INSERT INTO public.is_review (review_id, restaurant_id, user_id, rate, review, publicate, status, create_at) VALUES (6, 4, 4, 1, 'Słabe', true, true, '2024-05-02 08:50:49.592593');
INSERT INTO public.is_review (review_id, restaurant_id, user_id, rate, review, publicate, status, create_at) VALUES (7, 3, 4, 5, 'Jest super', true, true, '2024-05-02 11:23:30.014956');
INSERT INTO public.is_review (review_id, restaurant_id, user_id, rate, review, publicate, status, create_at) VALUES (8, 2, 4, 5, 'Testowa opinia z próbą Cross-site scripting
<script>alert(''ZOSTALES SHAKOWANY'')</script>', true, true, '2024-05-02 11:24:29.347897');


--
-- Data for Name: is_role; Type: TABLE DATA; Schema: public; Owner: docker
--

INSERT INTO public.is_role (role_id, name) VALUES (1, 'admin');
INSERT INTO public.is_role (role_id, name) VALUES (2, 'user');


--
-- Data for Name: is_user; Type: TABLE DATA; Schema: public; Owner: docker
--

INSERT INTO public.is_user (user_id, name, password, email, publicate, status, create_at, role_id) VALUES (1, 'Marcin', '$2y$10$FVskLFe3gbz9urYqKOUD7O.2JKJXIsjkWLxniV9t5MaQH/pKAwXF.', 'marcingodfryd@gmail.com', true, true, '2024-04-21 11:10:02.803125', 1);
INSERT INTO public.is_user (user_id, name, password, email, publicate, status, create_at, role_id) VALUES (2, 'test', '$2y$10$vskqysOJhr0GWmzHclamI.TnB9kGge.dFiOilelKAEEg.j7jhI9iW', 'admin@example.com', true, true, '2024-04-23 21:59:16.60773', 2);
INSERT INTO public.is_user (user_id, name, password, email, publicate, status, create_at, role_id) VALUES (3, 't', '$2y$10$Rpvr2rwWMMMms7LyCE/QLu5lbvdpp2vIr06rT.CU2nv9z6.IxGbdu', 'test@gmail.com', true, true, '2024-04-26 20:53:29.97449', 2);
INSERT INTO public.is_user (user_id, name, password, email, publicate, status, create_at, role_id) VALUES (4, 'Testowy user', '$2y$10$5ojtLCDFvSYdjbf4zbfGkem6X8XEFBQjfF0hJNBl4WuWkUDIAa2D2', 'test@user.pl', true, true, '2024-05-02 08:46:11.671839', 2);


--
-- Name: is_address_address_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.is_address_address_id_seq', 8, true);


--
-- Name: is_log_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.is_log_log_id_seq', 14, true);


--
-- Name: is_restaurant_restaurant_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.is_restaurant_restaurant_id_seq', 7, true);


--
-- Name: is_review_review_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.is_review_review_id_seq', 8, true);


--
-- Name: is_role_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.is_role_role_id_seq', 1, false);


--
-- Name: is_user_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.is_user_user_id_seq', 4, true);


--
-- Name: is_address is_address_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_address
    ADD CONSTRAINT is_address_pkey PRIMARY KEY (address_id);


--
-- Name: is_log is_log_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_log
    ADD CONSTRAINT is_log_pkey PRIMARY KEY (log_id);


--
-- Name: is_restaurant is_restaurant_address_id_key; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_restaurant
    ADD CONSTRAINT is_restaurant_address_id_key UNIQUE (address_id);


--
-- Name: is_restaurant is_restaurant_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_restaurant
    ADD CONSTRAINT is_restaurant_pkey PRIMARY KEY (restaurant_id);


--
-- Name: is_review is_review_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_review
    ADD CONSTRAINT is_review_pkey PRIMARY KEY (review_id);


--
-- Name: is_role is_role_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_role
    ADD CONSTRAINT is_role_pkey PRIMARY KEY (role_id);


--
-- Name: is_user is_user_pk; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_user
    ADD CONSTRAINT is_user_pk PRIMARY KEY (user_id);


--
-- Name: vw_restaurant_details _RETURN; Type: RULE; Schema: public; Owner: docker
--

CREATE OR REPLACE VIEW public.vw_restaurant_details AS
 SELECT r.restaurant_id,
    r.name,
    r.description,
    r.image,
    r.email,
    r.phone,
    r.website,
    a.address_id,
    a.street,
    a.city,
    a.postal_code,
    a.house_no,
    a.apartment_no,
    avg(re.rate) AS rate,
    r.publicate,
    r.status
   FROM ((public.is_restaurant r
     JOIN public.is_address a ON ((r.address_id = a.address_id)))
     LEFT JOIN public.is_review re ON ((r.restaurant_id = re.restaurant_id)))
  WHERE ((r.status = true) AND (r.publicate = true))
  GROUP BY r.restaurant_id, a.address_id;


--
-- Name: is_restaurant trg_restaurant_activity; Type: TRIGGER; Schema: public; Owner: docker
--

CREATE TRIGGER trg_restaurant_activity AFTER INSERT OR DELETE OR UPDATE ON public.is_restaurant FOR EACH ROW EXECUTE FUNCTION public.log_restaurant_activity();


--
-- Name: is_log is_log_is_restaurant_restaurant_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_log
    ADD CONSTRAINT is_log_is_restaurant_restaurant_id_fk FOREIGN KEY (restaurant_id) REFERENCES public.is_restaurant(restaurant_id);


--
-- Name: is_restaurant is_restaurant_is_address_address_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_restaurant
    ADD CONSTRAINT is_restaurant_is_address_address_id_fk FOREIGN KEY (address_id) REFERENCES public.is_address(address_id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: is_review is_review_is_restaurant_restaurant_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_review
    ADD CONSTRAINT is_review_is_restaurant_restaurant_id_fk FOREIGN KEY (restaurant_id) REFERENCES public.is_restaurant(restaurant_id);


--
-- Name: is_review is_review_is_user_user_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_review
    ADD CONSTRAINT is_review_is_user_user_id_fk FOREIGN KEY (user_id) REFERENCES public.is_user(user_id);


--
-- Name: is_user is_user_is_role_role_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.is_user
    ADD CONSTRAINT is_user_is_role_role_id_fk FOREIGN KEY (role_id) REFERENCES public.is_role(role_id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- PostgreSQL database dump complete
--

