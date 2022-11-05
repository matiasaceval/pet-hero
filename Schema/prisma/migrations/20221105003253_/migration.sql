/*
  Warnings:

  - Added the required column `fee` to the `Keeper` table without a default value. This is not possible if the table is not empty.
  - Added the required column `id_user` to the `Reservation` table without a default value. This is not possible if the table is not empty.
  - Added the required column `payment` to the `Reservation` table without a default value. This is not possible if the table is not empty.

*/
-- AlterTable
ALTER TABLE `keeper` ADD COLUMN `fee` INTEGER NOT NULL;

-- AlterTable
ALTER TABLE `reservation` ADD COLUMN `id_user` INTEGER NOT NULL,
    ADD COLUMN `payment` VARCHAR(191) NOT NULL;
