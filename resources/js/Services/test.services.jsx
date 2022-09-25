import Repository from './repository'

class TestServices{
    async getUserTest() {
        const endpoint = '/'
        const response = await Repository.get(endpoint)
        return response
    }
}
export default new TestServices()
