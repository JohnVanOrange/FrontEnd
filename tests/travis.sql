/* Standard user */
INSERT INTO  `users` (
`username` ,
`password` ,
`salt` ,
`email` ,
`type` ,
`theme` ,
`refresh`
)
VALUES (
'testuser',
'6df938a7ed2725c9327ebd44bd3ae281',
'YnAZwgUGSP9WreOD',
'test@example.com',
'1',
'light',
'0'
);
/* Admin user */
INSERT INTO  `users` (
`username` ,
`password` ,
`salt` ,
`email` ,
`type` ,
`theme` ,
`refresh`
)
VALUES (
'adminuser',
'6df938a7ed2725c9327ebd44bd3ae281',
'YnAZwgUGSP9WreOD',
'admin@example.com',
'2',
'light',
'0'
);