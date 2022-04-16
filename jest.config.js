module.exports = async () => {
    return {
        testEnvironment: "jsdom",
        roots: ["<rootDir>/resources/js/manager/tests"],
        verbose: true,
    };
};