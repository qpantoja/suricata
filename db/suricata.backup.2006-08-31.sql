--
-- PostgreSQL database dump
--

-- Started on 2006-08-31 19:27:50 CDT

SET client_encoding = 'UTF8';
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

ALTER TABLE ONLY public.dependency DROP CONSTRAINT task_needs;
ALTER TABLE ONLY public.dependency DROP CONSTRAINT task_idtask;
ALTER TABLE ONLY public.task DROP CONSTRAINT responsable;
ALTER TABLE ONLY public.changes_sheet DROP CONSTRAINT "owner";
ALTER TABLE ONLY public.project_has_user DROP CONSTRAINT iduser;
ALTER TABLE ONLY public.message DROP CONSTRAINT iduser;
ALTER TABLE ONLY public.advance DROP CONSTRAINT idtask;
ALTER TABLE ONLY public."user" DROP CONSTRAINT idsysrole;
ALTER TABLE ONLY public.project_has_user DROP CONSTRAINT idproject_role;
ALTER TABLE ONLY public.action_thread DROP CONSTRAINT idproject;
ALTER TABLE ONLY public.budget DROP CONSTRAINT idproject;
ALTER TABLE ONLY public.material DROP CONSTRAINT idproject;
ALTER TABLE ONLY public.proposal_solicitude DROP CONSTRAINT idproject;
ALTER TABLE ONLY public.changes_sheet DROP CONSTRAINT idproject;
ALTER TABLE ONLY public.project_has_user DROP CONSTRAINT idproject;
ALTER TABLE ONLY public.material_log DROP CONSTRAINT idmaterial;
ALTER TABLE ONLY public.budget_log DROP CONSTRAINT idbudget;
ALTER TABLE ONLY public.task DROP CONSTRAINT idaction_tread;
DROP INDEX public.user_fkidsysrole;
ALTER TABLE ONLY public.project_has_user DROP CONSTRAINT project_has_userpk;
ALTER TABLE ONLY public.dependency DROP CONSTRAINT pk_dependency;
ALTER TABLE ONLY public."user" DROP CONSTRAINT iduser;
ALTER TABLE ONLY public.task DROP CONSTRAINT idtask;
ALTER TABLE ONLY public.sysrole DROP CONSTRAINT idsysrole;
ALTER TABLE ONLY public.proposal_solicitude DROP CONSTRAINT idproposal_solitude;
ALTER TABLE ONLY public.proposal_solicitude DROP CONSTRAINT idproject_unique;
ALTER TABLE ONLY public.project_role DROP CONSTRAINT idproject_role;
ALTER TABLE ONLY public.project DROP CONSTRAINT idproject;
ALTER TABLE ONLY public.message DROP CONSTRAINT idmessage;
ALTER TABLE ONLY public.material_log DROP CONSTRAINT idmaterial_log;
ALTER TABLE ONLY public.material DROP CONSTRAINT idmaterial;
ALTER TABLE ONLY public.changes_sheet DROP CONSTRAINT idchanges_sheet;
ALTER TABLE ONLY public.budget_log DROP CONSTRAINT idbudget_log;
ALTER TABLE ONLY public.budget DROP CONSTRAINT idbudget;
ALTER TABLE ONLY public.advance DROP CONSTRAINT idadvance;
ALTER TABLE ONLY public.action_thread DROP CONSTRAINT idaction_tread;
DROP TABLE public."user";
DROP TABLE public.task;
DROP TABLE public.sysrole;
DROP TABLE public.proposal_solicitude;
DROP TABLE public.project_role;
DROP TABLE public.project_has_user;
DROP TABLE public.project;
DROP TABLE public.message;
DROP TABLE public.material_log;
DROP TABLE public.material;
DROP TABLE public.dependency;
DROP TABLE public.changes_sheet;
DROP TABLE public.budget_log;
DROP TABLE public.budget;
DROP TABLE public.advance;
DROP TABLE public.action_thread;
DROP SCHEMA public;
--
-- TOC entry 5 (class 2615 OID 56867)
-- Name: public; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO postgres;

--
-- TOC entry 1693 (class 0 OID 0)
-- Dependencies: 5
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'Standard public schema';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 1222 (class 1259 OID 56870)
-- Dependencies: 5
-- Name: action_thread; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE action_thread (
    idaction_thread serial NOT NULL,
    idproject integer NOT NULL,
    name character varying NOT NULL,
    responsable character varying NOT NULL,
    father_thread integer,
    deliverable character varying
);


ALTER TABLE public.action_thread OWNER TO suricata;

--
-- TOC entry 1695 (class 0 OID 0)
-- Dependencies: 1221
-- Name: action_thread_idaction_thread_seq; Type: SEQUENCE SET; Schema: public; Owner: suricata
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('action_thread', 'idaction_thread'), 22, true);


--
-- TOC entry 1224 (class 1259 OID 56878)
-- Dependencies: 1571 1572 5
-- Name: advance; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE advance (
    idadvance serial NOT NULL,
    idtask integer NOT NULL,
    description text NOT NULL,
    aproved boolean DEFAULT false NOT NULL,
    task_percent smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.advance OWNER TO suricata;

--
-- TOC entry 1696 (class 0 OID 0)
-- Dependencies: 1223
-- Name: advance_idadvance_seq; Type: SEQUENCE SET; Schema: public; Owner: suricata
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('advance', 'idadvance'), 7, true);


--
-- TOC entry 1226 (class 1259 OID 56888)
-- Dependencies: 5
-- Name: budget; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE budget (
    idbudget serial NOT NULL,
    idproject integer NOT NULL,
    amount double precision NOT NULL,
    expires date NOT NULL,
    description character varying NOT NULL,
    available integer NOT NULL
);


ALTER TABLE public.budget OWNER TO suricata;

--
-- TOC entry 1697 (class 0 OID 0)
-- Dependencies: 1225
-- Name: budget_idbudget_seq; Type: SEQUENCE SET; Schema: public; Owner: suricata
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('budget', 'idbudget'), 8, true);


--
-- TOC entry 1228 (class 1259 OID 56896)
-- Dependencies: 5
-- Name: budget_log; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE budget_log (
    idbudget_log serial NOT NULL,
    idbudget integer NOT NULL,
    date date NOT NULL,
    reason text NOT NULL,
    amount double precision NOT NULL
);


ALTER TABLE public.budget_log OWNER TO suricata;

--
-- TOC entry 1698 (class 0 OID 0)
-- Dependencies: 1227
-- Name: budget_log_idbudget_log_seq; Type: SEQUENCE SET; Schema: public; Owner: suricata
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('budget_log', 'idbudget_log'), 8, true);


--
-- TOC entry 1230 (class 1259 OID 56904)
-- Dependencies: 5
-- Name: changes_sheet; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE changes_sheet (
    idchanges_sheet serial NOT NULL,
    idproject integer NOT NULL,
    date date NOT NULL,
    description text NOT NULL,
    change_proposal text NOT NULL,
    reason text NOT NULL,
    tecnical_implication text,
    plan_impact text,
    "owner" character varying NOT NULL
);


ALTER TABLE public.changes_sheet OWNER TO suricata;

--
-- TOC entry 1699 (class 0 OID 0)
-- Dependencies: 1229
-- Name: changes_sheet_idchanges_sheet_seq; Type: SEQUENCE SET; Schema: public; Owner: suricata
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('changes_sheet', 'idchanges_sheet'), 26, true);


--
-- TOC entry 1231 (class 1259 OID 56910)
-- Dependencies: 5
-- Name: dependency; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE dependency (
    idtask smallint NOT NULL,
    needs smallint NOT NULL
);


ALTER TABLE public.dependency OWNER TO postgres;

--
-- TOC entry 1233 (class 1259 OID 56914)
-- Dependencies: 5
-- Name: material; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE material (
    idmaterial serial NOT NULL,
    idproject integer NOT NULL,
    description text NOT NULL,
    expires date NOT NULL,
    serial_number character varying
);


ALTER TABLE public.material OWNER TO suricata;

--
-- TOC entry 1700 (class 0 OID 0)
-- Dependencies: 1232
-- Name: material_idmaterial_seq; Type: SEQUENCE SET; Schema: public; Owner: suricata
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('material', 'idmaterial'), 4, true);


--
-- TOC entry 1235 (class 1259 OID 56922)
-- Dependencies: 5
-- Name: material_log; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE material_log (
    idmaterial_log serial NOT NULL,
    idmaterial integer NOT NULL,
    date date NOT NULL,
    reason text NOT NULL
);


ALTER TABLE public.material_log OWNER TO suricata;

--
-- TOC entry 1701 (class 0 OID 0)
-- Dependencies: 1234
-- Name: material_log_idmaterial_log_seq; Type: SEQUENCE SET; Schema: public; Owner: suricata
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('material_log', 'idmaterial_log'), 2, true);


--
-- TOC entry 1237 (class 1259 OID 56930)
-- Dependencies: 1579 5
-- Name: message; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE message (
    idmessage serial NOT NULL,
    iduser character varying NOT NULL,
    title character varying NOT NULL,
    date date NOT NULL,
    detail text NOT NULL,
    "read" boolean DEFAULT false NOT NULL
);


ALTER TABLE public.message OWNER TO suricata;

--
-- TOC entry 1702 (class 0 OID 0)
-- Dependencies: 1236
-- Name: message_idmessage_seq; Type: SEQUENCE SET; Schema: public; Owner: suricata
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('message', 'idmessage'), 74, true);


--
-- TOC entry 1239 (class 1259 OID 56939)
-- Dependencies: 5
-- Name: project; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE project (
    idproject serial NOT NULL,
    name character varying NOT NULL,
    state character varying NOT NULL,
    nomenclature character varying NOT NULL
);


ALTER TABLE public.project OWNER TO suricata;

--
-- TOC entry 1240 (class 1259 OID 56945)
-- Dependencies: 1581 5
-- Name: project_has_user; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE project_has_user (
    idproject integer NOT NULL,
    iduser character varying NOT NULL,
    idproject_role character varying DEFAULT 'no_role'::character varying NOT NULL
);


ALTER TABLE public.project_has_user OWNER TO suricata;

--
-- TOC entry 1703 (class 0 OID 0)
-- Dependencies: 1238
-- Name: project_idproject_seq; Type: SEQUENCE SET; Schema: public; Owner: suricata
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('project', 'idproject'), 13, true);


--
-- TOC entry 1241 (class 1259 OID 56951)
-- Dependencies: 1582 1583 1584 1585 1586 1587 1588 1589 1590 1591 1592 1593 1594 1595 1596 1597 1598 1599 1600 1601 1602 1603 1604 5
-- Name: project_role; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE project_role (
    idproject_role character varying NOT NULL,
    show_project_status boolean DEFAULT false NOT NULL,
    edit_project_status boolean DEFAULT false NOT NULL,
    evaluate_advance boolean DEFAULT false NOT NULL,
    create_changes_sheet boolean DEFAULT false NOT NULL,
    show_gantt boolean DEFAULT false NOT NULL,
    admin_tasks boolean DEFAULT false NOT NULL,
    show_p_solicitude boolean DEFAULT false NOT NULL,
    create_p_solicitude boolean DEFAULT false NOT NULL,
    delete_p_solicitude boolean DEFAULT false NOT NULL,
    modify_budget boolean DEFAULT false NOT NULL,
    admin_material boolean DEFAULT false NOT NULL,
    use_budget boolean DEFAULT false NOT NULL,
    create_report boolean DEFAULT false NOT NULL,
    show_job_entry boolean DEFAULT false NOT NULL,
    show_advances boolean DEFAULT false NOT NULL,
    generate_advances boolean DEFAULT false NOT NULL,
    show_changes_sheets boolean DEFAULT false NOT NULL,
    show_task_secuence boolean DEFAULT false NOT NULL,
    show_task_asignation boolean DEFAULT false NOT NULL,
    admin_human_r boolean DEFAULT false NOT NULL,
    delete_changes_sheet boolean DEFAULT false NOT NULL,
    edit_changes_sheet boolean DEFAULT false NOT NULL,
    edit_p_solicitude boolean DEFAULT false NOT NULL
);


ALTER TABLE public.project_role OWNER TO suricata;

--
-- TOC entry 1243 (class 1259 OID 56981)
-- Dependencies: 5
-- Name: proposal_solicitude; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE proposal_solicitude (
    idproposal_solicitude serial NOT NULL,
    idproject integer NOT NULL,
    job_description text NOT NULL,
    client_request text NOT NULL,
    deliverables text,
    supplies text,
    aprovals_required text,
    contract_type text,
    pay_conditions text,
    project_program text,
    instructions_content_format text,
    expiral_date date
);


ALTER TABLE public.proposal_solicitude OWNER TO suricata;

--
-- TOC entry 1704 (class 0 OID 0)
-- Dependencies: 1242
-- Name: proposal_solicitude_idproposal_solicitude_seq; Type: SEQUENCE SET; Schema: public; Owner: suricata
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('proposal_solicitude', 'idproposal_solicitude'), 8, true);


--
-- TOC entry 1244 (class 1259 OID 56987)
-- Dependencies: 1606 1607 1608 1609 1610 1611 1612 1613 1614 1615 1616 1617 1618 5
-- Name: sysrole; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE sysrole (
    idsysrole character varying(20) NOT NULL,
    create_user boolean DEFAULT false NOT NULL,
    edit_user boolean DEFAULT false NOT NULL,
    delete_user boolean DEFAULT false NOT NULL,
    edit_own_data boolean DEFAULT false NOT NULL,
    change_password boolean DEFAULT false NOT NULL,
    relate_user_project boolean DEFAULT false NOT NULL,
    backup boolean DEFAULT false NOT NULL,
    restore boolean DEFAULT false NOT NULL,
    show_users boolean DEFAULT false NOT NULL,
    show_projects boolean DEFAULT false NOT NULL,
    create_project boolean DEFAULT false NOT NULL,
    edit_project boolean DEFAULT false NOT NULL,
    delete_project boolean DEFAULT false NOT NULL
);


ALTER TABLE public.sysrole OWNER TO suricata;

--
-- TOC entry 1246 (class 1259 OID 57004)
-- Dependencies: 1620 5
-- Name: task; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE task (
    idtask serial NOT NULL,
    idaction_thread integer NOT NULL,
    begin_date date,
    end_date date,
    description text NOT NULL,
    programed_begin date NOT NULL,
    programed_end date NOT NULL,
    name character varying NOT NULL,
    responsable character varying NOT NULL,
    cost smallint DEFAULT 0 NOT NULL,
    deliverable character varying
);


ALTER TABLE public.task OWNER TO suricata;

--
-- TOC entry 1705 (class 0 OID 0)
-- Dependencies: 1245
-- Name: task_idtask_seq; Type: SEQUENCE SET; Schema: public; Owner: suricata
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('task', 'idtask'), 60, true);


--
-- TOC entry 1247 (class 1259 OID 57012)
-- Dependencies: 1621 5
-- Name: user; Type: TABLE; Schema: public; Owner: suricata; Tablespace: 
--

CREATE TABLE "user" (
    iduser character varying(15) NOT NULL,
    idsysrole character varying(20) DEFAULT 'no_role'::character varying,
    name character varying NOT NULL,
    father_lastname character varying(20) NOT NULL,
    mother_lastname character varying(20) NOT NULL,
    birthday date,
    address character varying(100),
    phone integer,
    email character varying(50),
    country character varying,
    state character varying,
    city character varying,
    "password" character(32)
);


ALTER TABLE public."user" OWNER TO suricata;

--
-- TOC entry 1675 (class 0 OID 56870)
-- Dependencies: 1222
-- Data for Name: action_thread; Type: TABLE DATA; Schema: public; Owner: suricata
--



--
-- TOC entry 1676 (class 0 OID 56878)
-- Dependencies: 1224
-- Data for Name: advance; Type: TABLE DATA; Schema: public; Owner: suricata
--



--
-- TOC entry 1677 (class 0 OID 56888)
-- Dependencies: 1226
-- Data for Name: budget; Type: TABLE DATA; Schema: public; Owner: suricata
--



--
-- TOC entry 1678 (class 0 OID 56896)
-- Dependencies: 1228
-- Data for Name: budget_log; Type: TABLE DATA; Schema: public; Owner: suricata
--



--
-- TOC entry 1679 (class 0 OID 56904)
-- Dependencies: 1230
-- Data for Name: changes_sheet; Type: TABLE DATA; Schema: public; Owner: suricata
--



--
-- TOC entry 1680 (class 0 OID 56910)
-- Dependencies: 1231
-- Data for Name: dependency; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 1681 (class 0 OID 56914)
-- Dependencies: 1233
-- Data for Name: material; Type: TABLE DATA; Schema: public; Owner: suricata
--



--
-- TOC entry 1682 (class 0 OID 56922)
-- Dependencies: 1235
-- Data for Name: material_log; Type: TABLE DATA; Schema: public; Owner: suricata
--



--
-- TOC entry 1683 (class 0 OID 56930)
-- Dependencies: 1237
-- Data for Name: message; Type: TABLE DATA; Schema: public; Owner: suricata
--



--
-- TOC entry 1684 (class 0 OID 56939)
-- Dependencies: 1239
-- Data for Name: project; Type: TABLE DATA; Schema: public; Owner: suricata
--

INSERT INTO project (idproject, name, state, nomenclature) VALUES (13, 'suricata', 'terminado', 'default');


--
-- TOC entry 1685 (class 0 OID 56945)
-- Dependencies: 1240
-- Data for Name: project_has_user; Type: TABLE DATA; Schema: public; Owner: suricata
--

INSERT INTO project_has_user (idproject, iduser, idproject_role) VALUES (13, 'quetzalcoatl', 'Lider de proyecto');


--
-- TOC entry 1686 (class 0 OID 56951)
-- Dependencies: 1241
-- Data for Name: project_role; Type: TABLE DATA; Schema: public; Owner: suricata
--

INSERT INTO project_role (idproject_role, show_project_status, edit_project_status, evaluate_advance, create_changes_sheet, show_gantt, admin_tasks, show_p_solicitude, create_p_solicitude, delete_p_solicitude, modify_budget, admin_material, use_budget, create_report, show_job_entry, show_advances, generate_advances, show_changes_sheets, show_task_secuence, show_task_asignation, admin_human_r, delete_changes_sheet, edit_changes_sheet, edit_p_solicitude) VALUES ('no_role', false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false);
INSERT INTO project_role (idproject_role, show_project_status, edit_project_status, evaluate_advance, create_changes_sheet, show_gantt, admin_tasks, show_p_solicitude, create_p_solicitude, delete_p_solicitude, modify_budget, admin_material, use_budget, create_report, show_job_entry, show_advances, generate_advances, show_changes_sheets, show_task_secuence, show_task_asignation, admin_human_r, delete_changes_sheet, edit_changes_sheet, edit_p_solicitude) VALUES ('Super usuario', true, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true);
INSERT INTO project_role (idproject_role, show_project_status, edit_project_status, evaluate_advance, create_changes_sheet, show_gantt, admin_tasks, show_p_solicitude, create_p_solicitude, delete_p_solicitude, modify_budget, admin_material, use_budget, create_report, show_job_entry, show_advances, generate_advances, show_changes_sheets, show_task_secuence, show_task_asignation, admin_human_r, delete_changes_sheet, edit_changes_sheet, edit_p_solicitude) VALUES ('Lider de proyecto', true, true, true, true, true, true, true, true, true, false, true, true, true, true, true, true, true, true, true, false, true, true, true);
INSERT INTO project_role (idproject_role, show_project_status, edit_project_status, evaluate_advance, create_changes_sheet, show_gantt, admin_tasks, show_p_solicitude, create_p_solicitude, delete_p_solicitude, modify_budget, admin_material, use_budget, create_report, show_job_entry, show_advances, generate_advances, show_changes_sheets, show_task_secuence, show_task_asignation, admin_human_r, delete_changes_sheet, edit_changes_sheet, edit_p_solicitude) VALUES ('Colaborador', true, false, false, false, true, false, false, false, false, false, false, false, false, true, false, true, false, true, true, false, false, false, false);
INSERT INTO project_role (idproject_role, show_project_status, edit_project_status, evaluate_advance, create_changes_sheet, show_gantt, admin_tasks, show_p_solicitude, create_p_solicitude, delete_p_solicitude, modify_budget, admin_material, use_budget, create_report, show_job_entry, show_advances, generate_advances, show_changes_sheets, show_task_secuence, show_task_asignation, admin_human_r, delete_changes_sheet, edit_changes_sheet, edit_p_solicitude) VALUES ('Responsable de linea de accion', true, false, true, false, true, true, false, false, false, false, false, false, true, true, true, true, true, true, true, false, false, false, false);
INSERT INTO project_role (idproject_role, show_project_status, edit_project_status, evaluate_advance, create_changes_sheet, show_gantt, admin_tasks, show_p_solicitude, create_p_solicitude, delete_p_solicitude, modify_budget, admin_material, use_budget, create_report, show_job_entry, show_advances, generate_advances, show_changes_sheets, show_task_secuence, show_task_asignation, admin_human_r, delete_changes_sheet, edit_changes_sheet, edit_p_solicitude) VALUES ('Seguimiento Administrativo', true, false, false, false, true, false, true, false, false, true, true, false, true, true, true, false, true, true, true, true, false, false, false);
INSERT INTO project_role (idproject_role, show_project_status, edit_project_status, evaluate_advance, create_changes_sheet, show_gantt, admin_tasks, show_p_solicitude, create_p_solicitude, delete_p_solicitude, modify_budget, admin_material, use_budget, create_report, show_job_entry, show_advances, generate_advances, show_changes_sheets, show_task_secuence, show_task_asignation, admin_human_r, delete_changes_sheet, edit_changes_sheet, edit_p_solicitude) VALUES ('Seguimiento General', true, false, false, false, true, false, true, false, false, true, true, false, true, true, true, false, true, true, true, true, false, false, false);
INSERT INTO project_role (idproject_role, show_project_status, edit_project_status, evaluate_advance, create_changes_sheet, show_gantt, admin_tasks, show_p_solicitude, create_p_solicitude, delete_p_solicitude, modify_budget, admin_material, use_budget, create_report, show_job_entry, show_advances, generate_advances, show_changes_sheets, show_task_secuence, show_task_asignation, admin_human_r, delete_changes_sheet, edit_changes_sheet, edit_p_solicitude) VALUES ('Seguimiento Tecnico', true, false, false, false, true, false, true, false, false, false, false, false, true, true, true, false, true, true, true, false, false, false, false);


--
-- TOC entry 1687 (class 0 OID 56981)
-- Dependencies: 1243
-- Data for Name: proposal_solicitude; Type: TABLE DATA; Schema: public; Owner: suricata
--



--
-- TOC entry 1688 (class 0 OID 56987)
-- Dependencies: 1244
-- Data for Name: sysrole; Type: TABLE DATA; Schema: public; Owner: suricata
--

INSERT INTO sysrole (idsysrole, create_user, edit_user, delete_user, edit_own_data, change_password, relate_user_project, backup, restore, show_users, show_projects, create_project, edit_project, delete_project) VALUES ('no_role', false, false, false, false, false, false, false, false, false, false, false, false, false);
INSERT INTO sysrole (idsysrole, create_user, edit_user, delete_user, edit_own_data, change_password, relate_user_project, backup, restore, show_users, show_projects, create_project, edit_project, delete_project) VALUES ('administrador', true, true, true, true, true, true, true, true, true, true, true, true, true);
INSERT INTO sysrole (idsysrole, create_user, edit_user, delete_user, edit_own_data, change_password, relate_user_project, backup, restore, show_users, show_projects, create_project, edit_project, delete_project) VALUES ('backup', false, false, false, true, true, false, true, true, false, false, false, false, false);
INSERT INTO sysrole (idsysrole, create_user, edit_user, delete_user, edit_own_data, change_password, relate_user_project, backup, restore, show_users, show_projects, create_project, edit_project, delete_project) VALUES ('usuario', false, false, false, true, true, false, false, false, false, false, false, false, false);


--
-- TOC entry 1689 (class 0 OID 57004)
-- Dependencies: 1246
-- Data for Name: task; Type: TABLE DATA; Schema: public; Owner: suricata
--



--
-- TOC entry 1690 (class 0 OID 57012)
-- Dependencies: 1247
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: suricata
--

INSERT INTO "user" (iduser, idsysrole, name, father_lastname, mother_lastname, birthday, address, phone, email, country, state, city, "password") VALUES ('suricata', 'administrador', 'suricata', 'suricata', 'suricata', '2006-04-05', '', NULL, '', 'México', 'México', 'Toluca', '92b38e1f9293bb40b484228b6dab502c');
INSERT INTO "user" (iduser, idsysrole, name, father_lastname, mother_lastname, birthday, address, phone, email, country, state, city, "password") VALUES ('quetzalcoatl', 'administrador', 'Quetzalcoatl', 'Pantoja', 'Hinojosa', '1983-04-05', 'some place at Toluca', 1234567, 'quetzalcoatl.pantoja@gmail.com', 'México', 'México', 'Toluca', '92b38e1f9293bb40b484228b6dab502c');


--
-- TOC entry 1623 (class 2606 OID 57019)
-- Dependencies: 1222 1222
-- Name: idaction_tread; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY action_thread
    ADD CONSTRAINT idaction_tread PRIMARY KEY (idaction_thread);


--
-- TOC entry 1625 (class 2606 OID 57021)
-- Dependencies: 1224 1224
-- Name: idadvance; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY advance
    ADD CONSTRAINT idadvance PRIMARY KEY (idadvance);


--
-- TOC entry 1627 (class 2606 OID 57023)
-- Dependencies: 1226 1226
-- Name: idbudget; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY budget
    ADD CONSTRAINT idbudget PRIMARY KEY (idbudget);


--
-- TOC entry 1629 (class 2606 OID 57025)
-- Dependencies: 1228 1228
-- Name: idbudget_log; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY budget_log
    ADD CONSTRAINT idbudget_log PRIMARY KEY (idbudget_log);


--
-- TOC entry 1631 (class 2606 OID 57027)
-- Dependencies: 1230 1230
-- Name: idchanges_sheet; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY changes_sheet
    ADD CONSTRAINT idchanges_sheet PRIMARY KEY (idchanges_sheet);


--
-- TOC entry 1635 (class 2606 OID 57029)
-- Dependencies: 1233 1233
-- Name: idmaterial; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY material
    ADD CONSTRAINT idmaterial PRIMARY KEY (idmaterial);


--
-- TOC entry 1637 (class 2606 OID 57031)
-- Dependencies: 1235 1235
-- Name: idmaterial_log; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY material_log
    ADD CONSTRAINT idmaterial_log PRIMARY KEY (idmaterial_log);


--
-- TOC entry 1639 (class 2606 OID 57033)
-- Dependencies: 1237 1237
-- Name: idmessage; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY message
    ADD CONSTRAINT idmessage PRIMARY KEY (idmessage);


--
-- TOC entry 1641 (class 2606 OID 57035)
-- Dependencies: 1239 1239
-- Name: idproject; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY project
    ADD CONSTRAINT idproject PRIMARY KEY (idproject);


--
-- TOC entry 1645 (class 2606 OID 57037)
-- Dependencies: 1241 1241
-- Name: idproject_role; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY project_role
    ADD CONSTRAINT idproject_role PRIMARY KEY (idproject_role);


--
-- TOC entry 1647 (class 2606 OID 57039)
-- Dependencies: 1243 1243
-- Name: idproject_unique; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY proposal_solicitude
    ADD CONSTRAINT idproject_unique UNIQUE (idproject);


--
-- TOC entry 1649 (class 2606 OID 57041)
-- Dependencies: 1243 1243
-- Name: idproposal_solitude; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY proposal_solicitude
    ADD CONSTRAINT idproposal_solitude PRIMARY KEY (idproposal_solicitude);


--
-- TOC entry 1651 (class 2606 OID 57043)
-- Dependencies: 1244 1244
-- Name: idsysrole; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY sysrole
    ADD CONSTRAINT idsysrole PRIMARY KEY (idsysrole);


--
-- TOC entry 1653 (class 2606 OID 57045)
-- Dependencies: 1246 1246
-- Name: idtask; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY task
    ADD CONSTRAINT idtask PRIMARY KEY (idtask);


--
-- TOC entry 1655 (class 2606 OID 57047)
-- Dependencies: 1247 1247
-- Name: iduser; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT iduser PRIMARY KEY (iduser);


--
-- TOC entry 1633 (class 2606 OID 57049)
-- Dependencies: 1231 1231 1231
-- Name: pk_dependency; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY dependency
    ADD CONSTRAINT pk_dependency PRIMARY KEY (idtask, needs);


--
-- TOC entry 1643 (class 2606 OID 57051)
-- Dependencies: 1240 1240 1240
-- Name: project_has_userpk; Type: CONSTRAINT; Schema: public; Owner: suricata; Tablespace: 
--

ALTER TABLE ONLY project_has_user
    ADD CONSTRAINT project_has_userpk PRIMARY KEY (idproject, iduser);


--
-- TOC entry 1656 (class 1259 OID 57052)
-- Dependencies: 1247
-- Name: user_fkidsysrole; Type: INDEX; Schema: public; Owner: suricata; Tablespace: 
--

CREATE INDEX user_fkidsysrole ON "user" USING btree (idsysrole);


--
-- TOC entry 1673 (class 2606 OID 57053)
-- Dependencies: 1246 1222 1622
-- Name: idaction_tread; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY task
    ADD CONSTRAINT idaction_tread FOREIGN KEY (idaction_thread) REFERENCES action_thread(idaction_thread) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1660 (class 2606 OID 57058)
-- Dependencies: 1228 1226 1626
-- Name: idbudget; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY budget_log
    ADD CONSTRAINT idbudget FOREIGN KEY (idbudget) REFERENCES budget(idbudget) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1666 (class 2606 OID 57063)
-- Dependencies: 1235 1233 1634
-- Name: idmaterial; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY material_log
    ADD CONSTRAINT idmaterial FOREIGN KEY (idmaterial) REFERENCES material(idmaterial) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1670 (class 2606 OID 57068)
-- Dependencies: 1240 1239 1640
-- Name: idproject; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY project_has_user
    ADD CONSTRAINT idproject FOREIGN KEY (idproject) REFERENCES project(idproject) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1662 (class 2606 OID 57073)
-- Dependencies: 1230 1239 1640
-- Name: idproject; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY changes_sheet
    ADD CONSTRAINT idproject FOREIGN KEY (idproject) REFERENCES project(idproject) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1671 (class 2606 OID 57078)
-- Dependencies: 1243 1239 1640
-- Name: idproject; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY proposal_solicitude
    ADD CONSTRAINT idproject FOREIGN KEY (idproject) REFERENCES project(idproject) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1665 (class 2606 OID 57083)
-- Dependencies: 1233 1239 1640
-- Name: idproject; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY material
    ADD CONSTRAINT idproject FOREIGN KEY (idproject) REFERENCES project(idproject) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1659 (class 2606 OID 57088)
-- Dependencies: 1226 1239 1640
-- Name: idproject; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY budget
    ADD CONSTRAINT idproject FOREIGN KEY (idproject) REFERENCES project(idproject) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1657 (class 2606 OID 57093)
-- Dependencies: 1222 1239 1640
-- Name: idproject; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY action_thread
    ADD CONSTRAINT idproject FOREIGN KEY (idproject) REFERENCES project(idproject) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1669 (class 2606 OID 57098)
-- Dependencies: 1240 1241 1644
-- Name: idproject_role; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY project_has_user
    ADD CONSTRAINT idproject_role FOREIGN KEY (idproject_role) REFERENCES project_role(idproject_role) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 1674 (class 2606 OID 57103)
-- Dependencies: 1247 1244 1650
-- Name: idsysrole; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT idsysrole FOREIGN KEY (idsysrole) REFERENCES sysrole(idsysrole) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- TOC entry 1658 (class 2606 OID 57108)
-- Dependencies: 1224 1246 1652
-- Name: idtask; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY advance
    ADD CONSTRAINT idtask FOREIGN KEY (idtask) REFERENCES task(idtask) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1667 (class 2606 OID 57113)
-- Dependencies: 1237 1247 1654
-- Name: iduser; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY message
    ADD CONSTRAINT iduser FOREIGN KEY (iduser) REFERENCES "user"(iduser) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1668 (class 2606 OID 57118)
-- Dependencies: 1240 1247 1654
-- Name: iduser; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY project_has_user
    ADD CONSTRAINT iduser FOREIGN KEY (iduser) REFERENCES "user"(iduser) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1661 (class 2606 OID 57123)
-- Dependencies: 1230 1247 1654
-- Name: owner; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY changes_sheet
    ADD CONSTRAINT "owner" FOREIGN KEY ("owner") REFERENCES "user"(iduser) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 1672 (class 2606 OID 57128)
-- Dependencies: 1246 1247 1654
-- Name: responsable; Type: FK CONSTRAINT; Schema: public; Owner: suricata
--

ALTER TABLE ONLY task
    ADD CONSTRAINT responsable FOREIGN KEY (responsable) REFERENCES "user"(iduser) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 1664 (class 2606 OID 57133)
-- Dependencies: 1231 1246 1652
-- Name: task_idtask; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dependency
    ADD CONSTRAINT task_idtask FOREIGN KEY (idtask) REFERENCES task(idtask) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1663 (class 2606 OID 57138)
-- Dependencies: 1231 1246 1652
-- Name: task_needs; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY dependency
    ADD CONSTRAINT task_needs FOREIGN KEY (needs) REFERENCES task(idtask) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1694 (class 0 OID 0)
-- Dependencies: 5
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2006-08-31 19:27:50 CDT

--
-- PostgreSQL database dump complete
--

