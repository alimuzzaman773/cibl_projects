CREATE TABLE `files` (
  `fileId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `fileName` varchar(255) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updatedBy` int(11) NOT NULL,
  `creationDtTm` datetime NOT NULL,
  `updateDtTm` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
ALTER TABLE `files`
  ADD PRIMARY KEY (`fileId`);

--
ALTER TABLE `files`
  MODIFY `fileId` int(11) NOT NULL AUTO_INCREMENT;