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
'94feaf586c0d13d9359b7192ec1d6143',
'F8udsbhmtcx4gMqW',
'admin@example.com',
'2',
'light',
'0'
);